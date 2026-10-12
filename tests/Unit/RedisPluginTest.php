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

namespace Micro\Plugin\Cache\Test\Unit;

use Micro\Kernel\App\AppKernel;
use Micro\Plugin\Redis\Facade\RedisFacadeInterface;
use Micro\Plugin\Redis\RedisPlugin;
use PHPUnit\Framework\TestCase;

class RedisPluginTest extends TestCase
{
    public function testPlugin(): void
    {
        $kernel = new AppKernel(
            [],
            [
                RedisPlugin::class,
            ]
        );

        $kernel->run();

        $facade = $kernel->container()->get(RedisFacadeInterface::class);
        $facadeDeprecated = $kernel->container()->get(\Micro\Plugin\Redis\RedisFacadeInterface::class);

        $this->assertSame($facade, $facadeDeprecated);

        $this->expectException(\RedisException::class);
        $this->expectExceptionMessage('Connection refused');

        $kernel->container()->get(RedisFacadeInterface::class)->getClient();
    }
}
