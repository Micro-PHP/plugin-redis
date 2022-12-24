<?php

namespace Micro\Plugin\Redis\Business\Redis;

use Micro\Plugin\Redis\Redis\RedisInterface;

interface RedisFactoryInterface
{
    /**
     * @return RedisInterface
     */
    public function create(): RedisInterface;
}
