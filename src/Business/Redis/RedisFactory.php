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

namespace Micro\Plugin\Redis\Business\Redis;

use Micro\Plugin\Redis\Configuration\AuthorizationConfiguration;
use Micro\Plugin\Redis\Configuration\AuthorizationConfigurationInterface;
use Micro\Plugin\Redis\Configuration\ClientOptionsConfigurationInterface;
use Micro\Plugin\Redis\Configuration\RedisClientConfigurationInterface;

/**
 * @author Stanislau Komar <kost@micro-php.net>
 */
class RedisFactory implements RedisFactoryInterface
{
    public function create(RedisClientConfigurationInterface $clientConfiguration): \Redis
    {
        $redis = new \Redis();

        $this->initialize($redis, $clientConfiguration);

        return $redis;
    }

    /**
     * @throws \RedisException
     */
    protected function initialize(\Redis $redis, RedisClientConfigurationInterface $configuration): void
    {
        $this->connect($redis, $configuration);
        $this->authenticate($redis, $configuration->authorizationConfiguration());
        $this->setOptions($redis, $configuration->clientOptionsConfiguration());
    }

    /**
     * @throws \RedisException
     */
    protected function connect(\Redis $redis, RedisClientConfigurationInterface $configuration): void
    {
        $connectionMethod = $this->getConnectionMethod($configuration);

        if (RedisClientConfigurationInterface::CONNECTION_TYPE_UNIX === $configuration->connectionType()) {
            $redis->{$connectionMethod}($configuration->host());

            return;
        }

        \call_user_func(
            [$redis, $connectionMethod], // @phpstan-ignore-line
            $configuration->host(),
            $configuration->port(),
            $configuration->connectionTimeout(),
            $this->getPersistentId($configuration),
            $configuration->retryInterval(),
            $configuration->readTimeout(),
        );
    }

    /**
     * @throws \RedisException
     */
    protected function authenticate(\Redis $redis, AuthorizationConfigurationInterface $configuration): void
    {
        $password = $configuration->password();
        if (null === $password) {
            return;
        }

        $username = $configuration->username();
        // Password-only AUTH uses Redis's default user and also supports Redis < 6.
        $credentials = null === $username || '' === $username || AuthorizationConfiguration::USERNAME_DEFAULT === $username
            ? $password
            : [$username, $password];

        if (!$redis->auth($credentials)) {
            throw new \RedisException('Redis authentication failed.');
        }
    }

    /**
     * @throws \RedisException
     */
    protected function setOptions(\Redis $redis, ClientOptionsConfigurationInterface $configuration): void
    {
        $redis->setOption(\Redis::OPT_SERIALIZER, $this->getRedisOptionValue($configuration->serializer()));
        $redis->setOption(\Redis::OPT_PREFIX, $configuration->prefix());
    }

    protected function getRedisOptionValue(string $redisOption): int
    {
        return \constant("Redis::$redisOption");
    }

    protected function getConnectionMethod(RedisClientConfigurationInterface $configuration): string
    {
        return $configuration->reuseConnection() ? 'pconnect' : 'connect';
    }

    protected function getPersistentId(RedisClientConfigurationInterface $configuration): ?string
    {
        return $configuration->reuseConnection() ? $configuration->name() : null;
    }
}
