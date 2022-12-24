<?php

namespace Micro\Plugin\Redis;

use Micro\Plugin\Redis\Redis\RedisInterface;

interface RedisFacadeInterface
{
    /**
     * @param string $clientName
     * @return RedisInterface
     */
    public function getClient(string $clientName = RedisPluginConfiguration::CLIENT_DEFAULT): RedisInterface;
}
