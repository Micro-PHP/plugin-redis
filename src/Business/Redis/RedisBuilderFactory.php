<?php

namespace Micro\Plugin\Redis\Business\Redis;

use Micro\Plugin\Redis\RedisPluginConfigurationInterface;

class RedisBuilderFactory implements RedisBuilderFactoryInterface
{
    /**
     * @param RedisPluginConfigurationInterface $configuration
     * @param RedisFactoryInterface $redisFactory
     */
    public function __construct(
        private readonly RedisPluginConfigurationInterface $configuration,
        private readonly RedisFactoryInterface $redisFactory
    )
    {
    }

    /**
     * {@inheritDoc}
     */
    public function createBuilder(): RedisBuilderInterface
    {
        return new RedisBuilder(
            $this->configuration,
            $this->redisFactory
        );
    }
}
