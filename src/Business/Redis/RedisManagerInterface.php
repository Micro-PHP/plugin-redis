<?php

namespace Micro\Plugin\Redis\Business\Redis;

interface RedisManagerInterface
{
    /**
     * @param string $clientName
     * @return \Redis
     */
    public function getClient(string $clientName): \Redis;
}
