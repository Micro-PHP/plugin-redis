<?php

namespace Micro\Plugin\Redis;

use Micro\Component\DependencyInjection\Container;
use Micro\Framework\Kernel\Plugin\AbstractPlugin;
use Micro\Plugin\Redis\Business\Redis\RedisBuilderFactory;
use Micro\Plugin\Redis\Business\Redis\RedisBuilderFactoryInterface;
use Micro\Plugin\Redis\Business\Redis\RedisFactory;
use Micro\Plugin\Redis\Business\Redis\RedisFactoryInterface;
use Micro\Plugin\Redis\Business\Redis\RedisManager;
use Micro\Plugin\Redis\Business\Redis\RedisManagerInterface;

/**
 * @method RedisPluginConfigurationInterface configuration()
 */
class RedisPlugin extends AbstractPlugin
{
    /**
     * {@inheritDoc}
     */
    public function provideDependencies(Container $container): void
    {
        $container->register(RedisFacadeInterface::class, function (Container $container) {
            return $this->createRedisFacade();
        });
    }

    /**
     * @return RedisFacadeInterface
     */
    protected function createRedisFacade(): RedisFacadeInterface
    {
        return new RedisFacade($this->createRedisManager());
    }

    /**
     * @return RedisManagerInterface
     */
    protected function createRedisManager(): RedisManagerInterface
    {
        return new RedisManager($this->createRedisBuilderFactory());
    }

    /**
     * @return RedisBuilderFactoryInterface
     */
    protected function createRedisBuilderFactory(): RedisBuilderFactoryInterface
    {
        return new RedisBuilderFactory(
            $this->configuration(),
            $this->createRedisFactory()
        );
    }

    /**
     * @return RedisFactoryInterface
     */
    protected function createRedisFactory(): RedisFactoryInterface
    {
        return new RedisFactory();
    }
}
