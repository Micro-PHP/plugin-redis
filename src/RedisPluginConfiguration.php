<?php

namespace Micro\Plugin\Redis;

use Micro\Framework\Kernel\Configuration\PluginConfiguration;
use Micro\Plugin\Redis\Configuration\RedisClientConfiguration;
use Micro\Plugin\Redis\Configuration\RedisClientConfigurationInterface;

class RedisPluginConfiguration extends PluginConfiguration implements RedisPluginConfigurationInterface
{

    protected const CFG_CLIENT_LIST = 'REDIS_CLIENTS';

    public const CLIENT_DEFAULT = 'default';

    /**
     * @return string[]
     */
    public function getClientList(): array
    {
        return $this->explodeStringToArray(
            $this->configuration->get(self::CFG_CLIENT_LIST, self::CLIENT_DEFAULT)
        );
    }

    /**
     * @param string $clientName
     * @return RedisClientConfigurationInterface
     */
    public function getClientConfiguration(string $clientName): RedisClientConfigurationInterface
    {
        if(!in_array($clientName, $this->getClientList(), true)) {
            throw new \InvalidArgumentException(
                'Redis client is not defined in the environment file. Please, append connection id to "%s"',
                self::CFG_CLIENT_LIST
            );
        }

        return new RedisClientConfiguration($this->configuration, $clientName);
    }
}
