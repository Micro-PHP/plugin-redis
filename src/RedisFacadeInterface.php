<?php

namespace Micro\Plugin\Redis;

use \Redis;

interface RedisFacadeInterface
{
    /**
     * @param string $clientName
     * @return \Redis
     */
    public function getClient(string $clientName = RedisPluginConfiguration::CLIENT_DEFAULT): Redis;
}
