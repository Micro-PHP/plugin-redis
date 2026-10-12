<?php

declare(strict_types=1);

/*
 *  This file is part of the Micro framework package.
 *
 *  (c) Stanislau Komar <kost@micro-php.net>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Micro\Plugin\Cache\Test\Unit\Business\Redis;

use Micro\Framework\Kernel\Configuration\DefaultApplicationConfiguration;
use Micro\Plugin\Redis\Business\Redis\RedisFactory;
use Micro\Plugin\Redis\Configuration\AuthorizationConfigurationInterface;
use Micro\Plugin\Redis\Configuration\ClientOptionsConfiguration;
use Micro\Plugin\Redis\Configuration\RedisClientConfiguration;
use Micro\Plugin\Redis\Configuration\RedisClientConfigurationInterface;
use PHPUnit\Framework\TestCase;

class RedisFactoryTest extends TestCase
{
    /**
     * @dataProvider provideConnections
     */
    public function testAuthentication(string $connectionType, bool $persistent, array $credentials, $expectedAuth): void
    {
        $configuration = new RedisClientConfiguration(new DefaultApplicationConfiguration(array_merge([
            'REDIS_TEST_CONNECTION_TYPE' => $connectionType,
            'REDIS_TEST_CONNECTION_REUSE' => $persistent,
            'REDIS_TEST_HOST' => 'unix' === $connectionType ? '/tmp/redis.sock' : 'localhost',
            'REDIS_TEST_OPT_PREFIX' => 'test:',
        ], $credentials)), 'test');

        $redis = $this->createMock(\Redis::class);
        $method = $persistent ? 'pconnect' : 'connect';
        $redis->expects($this->once())->method($method)->willReturn(true);
        $redis->expects($this->never())->method($persistent ? 'connect' : 'pconnect');
        $redis->expects($this->exactly(null === $expectedAuth ? 0 : 1))
            ->method('auth')->with($expectedAuth)->willReturn(true);
        $redis->expects($this->exactly(2))->method('setOption')->withConsecutive(
            [\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_NONE],
            [\Redis::OPT_PREFIX, 'test:'],
        )->willReturn(true);

        $this->initialize($redis, $configuration);
    }

    public function provideConnections(): iterable
    {
        $credentials = [
            'no auth' => [[], null],
            'password only' => [['REDIS_TEST_AUTH_PASSWORD' => 'secret'], 'secret'],
            'default user' => [[
                'REDIS_TEST_AUTH_USERNAME' => 'default',
                'REDIS_TEST_AUTH_PASSWORD' => 'secret',
            ], 'secret'],
            'ACL user' => [[
                'REDIS_TEST_AUTH_USERNAME' => 'app',
                'REDIS_TEST_AUTH_PASSWORD' => 'secret',
            ], ['app', 'secret']],
            'empty username' => [[
                'REDIS_TEST_AUTH_USERNAME' => '',
                'REDIS_TEST_AUTH_PASSWORD' => 'secret',
            ], 'secret'],
            'zero password' => [['REDIS_TEST_AUTH_PASSWORD' => '0'], '0'],
            'empty password' => [['REDIS_TEST_AUTH_PASSWORD' => ''], ''],
        ];

        foreach (['network', 'unix'] as $type) {
            foreach ([false, true] as $persistent) {
                foreach ($credentials as $name => [$config, $auth]) {
                    yield \sprintf('%s %s %s', $type, $persistent ? 'persistent' : 'regular', $name) => [
                        $type, $persistent, $config, $auth,
                    ];
                }
            }
        }
    }

    public function testNullUsernameUsesPasswordOnlyAuthentication(): void
    {
        $auth = $this->createMock(AuthorizationConfigurationInterface::class);
        $auth->method('username')->willReturn(null);
        $auth->method('password')->willReturn('secret');

        $configuration = $this->createMock(RedisClientConfigurationInterface::class);
        $configuration->method('authorizationConfiguration')->willReturn($auth);
        $configuration->method('clientOptionsConfiguration')->willReturn(
            new ClientOptionsConfiguration(new DefaultApplicationConfiguration([]), 'test'),
        );

        $redis = $this->createMock(\Redis::class);
        $redis->expects($this->once())->method('auth')->with('secret')->willReturn(true);

        $this->initialize($redis, $configuration);
    }

    public function testFailedAuthenticationRejectsClient(): void
    {
        $configuration = new RedisClientConfiguration(new DefaultApplicationConfiguration([
            'REDIS_TEST_AUTH_PASSWORD' => 'wrong-password',
        ]), 'test');
        $redis = $this->createMock(\Redis::class);
        $redis->expects($this->once())->method('auth')->willReturn(false);
        $redis->expects($this->never())->method('setOption');

        $this->expectException(\RedisException::class);
        $this->expectExceptionMessage('Redis authentication failed.');

        $this->initialize($redis, $configuration);
    }

    public function testAuthenticationExceptionRejectsClient(): void
    {
        $configuration = new RedisClientConfiguration(new DefaultApplicationConfiguration([
            'REDIS_TEST_AUTH_PASSWORD' => 'wrong-password',
        ]), 'test');
        $redis = $this->createMock(\Redis::class);
        $redis->expects($this->once())->method('auth')->willThrowException(new \RedisException('WRONGPASS'));
        $redis->expects($this->never())->method('setOption');

        $this->expectException(\RedisException::class);
        $this->expectExceptionMessage('WRONGPASS');

        $this->initialize($redis, $configuration);
    }

    public function testCreate(): void
    {
        $this->expectException(\RedisException::class);

        $appCfg = new DefaultApplicationConfiguration([]);
        $cfg = new RedisClientConfiguration($appCfg, 'test');

        $factory = new RedisFactory();
        $factory->create($cfg);
    }

    private function initialize(\Redis $redis, RedisClientConfigurationInterface $configuration): void
    {
        $factory = new class extends RedisFactory {
            public function initialize(\Redis $redis, RedisClientConfigurationInterface $configuration): void
            {
                parent::initialize($redis, $configuration);
            }
        };

        $factory->initialize($redis, $configuration);
    }
}
