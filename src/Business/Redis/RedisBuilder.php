<?php

namespace Micro\Plugin\Redis\Business\Redis;

use Micro\Plugin\Redis\Configuration\ClientOptionsConfigurationInterface;
use Micro\Plugin\Redis\Configuration\RedisClientConfigurationInterface;
use Micro\Plugin\Redis\Redis\RedisInterface;
use Micro\Plugin\Redis\RedisPluginConfigurationInterface;
use Redis;

/**
 * @author Stanislau Komar <head.trackingsoft@gmail.com>
 */
class RedisBuilder implements RedisBuilderInterface
{
    /**
     * @param RedisPluginConfigurationInterface $pluginConfiguration
     * @param RedisFactoryInterface $redisFactory
     */
    public function __construct(
        private readonly RedisPluginConfigurationInterface $pluginConfiguration,
        private readonly RedisFactoryInterface $redisFactory
    )
    {
    }

    /**
     * {@inheritDoc}
     */
    public function create(string $redisAlias): RedisInterface
    {
        $clientConfiguration = $this->pluginConfiguration->getClientConfiguration($redisAlias);
        $redis               = $this->redisFactory->create();

        $this->initialize($redis, $clientConfiguration);

        return $redis;
    }

    /**
     * @param RedisInterface $redis
     * @param RedisClientConfigurationInterface $configuration
     *
     * @return void
     */
    protected function initialize(RedisInterface $redis, RedisClientConfigurationInterface $configuration): void
    {
        $connectionMethod = $this->getConnectionMethod($configuration);

        if($configuration->connectionType() === RedisClientConfigurationInterface::CONNECTION_TYPE_UNIX) {
            $redis->{$connectionMethod}($configuration->host());

            return;
        }

        $redis->{$connectionMethod}(
            $configuration->host(),
            $configuration->port(),
            $configuration->connectionTimeout(),
            $this->getPersistentId($configuration),
            $configuration->retryInterval(),
            $configuration->readTimeout(),
        );

        $this->setOptions($redis, $configuration->clientOptionsConfiguration());
    }

    /**
     * @param RedisInterface $redis
     * @param ClientOptionsConfigurationInterface $configuration
     *
     * @return void
     */
    protected function setOptions(RedisInterface $redis, ClientOptionsConfigurationInterface $configuration): void
    {
        $redis->setOption(Redis::OPT_SERIALIZER, $this->getRedisOptionValue($configuration->serializer()));
        $redis->setOption(Redis::OPT_PREFIX, $configuration->prefix());
    }

    /**
     * @param string $redisOption
     *
     * @return int
     */
    protected function getRedisOptionValue(string $redisOption): int
    {
        return constant("Redis::$redisOption");
    }

    /**
     * @param RedisClientConfigurationInterface $configuration
     *
     * @return string
     */
    protected function getConnectionMethod(RedisClientConfigurationInterface $configuration): string
    {
        return $configuration->reuseConnection() ? 'pconnect': 'connect';
    }

    /**
     * @param RedisClientConfigurationInterface $configuration
     *
     * @return string|null
     */
    protected function getPersistentId(RedisClientConfigurationInterface $configuration): ?string
    {
        return $configuration->reuseConnection() ? $configuration->name(): null;
    }
}
