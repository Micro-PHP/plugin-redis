<?php

namespace Micro\Plugin\Redis;

use Micro\Plugin\Redis\Business\Redis\RedisManagerInterface;

class RedisFacade implements RedisFacadeInterface
{
    /**
     * @param RedisManagerInterface $redisManager
     */
    public function __construct(private RedisManagerInterface $redisManager)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function getClient(string $clientName = RedisPluginConfiguration::CLIENT_DEFAULT): \Redis
    {
        return $this->redisManager->getClient($clientName);
    }
}
