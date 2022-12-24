<?php

namespace Micro\Plugin\Redis\Business\Redis;

use Micro\Plugin\Redis\Redis\RedisInterface;

interface RedisBuilderInterface
{
    /**
     * @param string $redisAlias
     * @return RedisInterface
     */
    public function create(string $redisAlias): RedisInterface;
}
