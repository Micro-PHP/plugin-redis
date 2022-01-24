<?php

namespace Micro\Plugin\Redis\Business\Redis;

use Micro\Plugin\Redis\RedisPluginConfiguration;

class RedisBuilderFactory implements RedisBuilderFactoryInterface
{
    /**
     * @param RedisPluginConfiguration $configuration
     * @param RedisFactoryInterface $redisFactory
     */
    public function __construct(
        private RedisPluginConfiguration $configuration,
        private RedisFactoryInterface $redisFactory
    ) {}

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
