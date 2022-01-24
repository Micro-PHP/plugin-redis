<?php

namespace Micro\Plugin\Redis\Business\Redis;

interface RedisBuilderInterface
{
    /**
     * @param string $redisAlias
     * @return \Redis
     */
    public function create(string $redisAlias): \Redis;
}
