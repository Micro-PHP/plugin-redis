<?php

namespace Micro\Plugin\Redis\Business\Redis;

use Micro\Plugin\Redis\Redis\RedisInterface;

interface RedisManagerInterface
{
    /**
     * @param string $clientName
     *
     * @return RedisInterface
     */
    public function getClient(string $clientName): RedisInterface;
}
