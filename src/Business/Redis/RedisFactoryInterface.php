<?php

namespace Micro\Plugin\Redis\Business\Redis;

interface RedisFactoryInterface
{
    /**
     * @return \Redis
     */
    public function create(): \Redis;
}
