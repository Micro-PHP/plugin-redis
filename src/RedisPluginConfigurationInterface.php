<?php

namespace Micro\Plugin\Redis;

use Micro\Framework\Kernel\Configuration\PluginConfigurationInterface;
use Micro\Plugin\Redis\Configuration\RedisClientConfigurationInterface;

interface RedisPluginConfigurationInterface extends PluginConfigurationInterface
{
    /**
     * @return string[]
     */
    public function getClientList(): array;

    /**
     * @param string $clientName
     * @return RedisClientConfigurationInterface
     */
    public function getClientConfiguration(string $clientName): RedisClientConfigurationInterface;

}
