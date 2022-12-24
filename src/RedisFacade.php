<?php

namespace Micro\Plugin\Redis;

use Micro\Plugin\Redis\Business\Redis\RedisManagerInterface;
use Micro\Plugin\Redis\Redis\RedisInterface;

class RedisFacade implements RedisFacadeInterface
{
    /**
     * @param RedisManagerInterface $redisManager
     */
    public function __construct(private readonly RedisManagerInterface $redisManager)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function getClient(string $clientName = RedisPluginConfiguration::CLIENT_DEFAULT): RedisInterface
    {
        return $this->redisManager->getClient($clientName);
    }
}
