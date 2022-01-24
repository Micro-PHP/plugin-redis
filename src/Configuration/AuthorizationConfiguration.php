<?php

namespace Micro\Plugin\Redis\Configuration;

use Micro\Framework\Kernel\Configuration\PluginRoutingKeyConfiguration;

class AuthorizationConfiguration extends PluginRoutingKeyConfiguration implements AuthorizationConfigurationInterface
{
    protected const CFG_USERNAME = 'REDIS_%s_AUTH_USERNAME';
    protected const CFG_PASSWORD = 'REDIS_%s_AUTH_PASSWORD';

    /**
     * {@inheritDoc}
     */
    public function username(): ?string
    {
        return $this->get(self::CFG_USERNAME);
    }

    /**
     * {@inheritDoc}
     */
    public function password(): ?string
    {
        return $this->get(self::CFG_PASSWORD);
    }
}
