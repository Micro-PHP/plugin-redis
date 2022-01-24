<?php

namespace Micro\Plugin\Redis\Configuration;

use Micro\Framework\Kernel\Configuration\PluginRoutingKeyConfiguration;

class SslConfiguration extends PluginRoutingKeyConfiguration implements SslConfigurationInterface
{

    protected const CFG_SSL_ENABLED = 'REDIS_%s_SSL_ENABLED';
    protected const CFG_SSL_VERIFY = 'REDIS_%s_SSL_VERIFY';

    public const SSL_ENABLED_DEFAULT = false;
    public const SSL_VERIFY_DEFAULT = false;

    /**
     * @return bool
     */
    public function verify(): bool
    {
        return $this->get(self::CFG_SSL_VERIFY, self::SSL_VERIFY_DEFAULT);
    }

    /**
     * @return bool
     */
    public function enabled(): bool
    {
        return $this->get(self::CFG_SSL_ENABLED, self::SSL_ENABLED_DEFAULT);
    }
}
