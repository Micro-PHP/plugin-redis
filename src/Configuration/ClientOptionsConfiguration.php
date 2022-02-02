<?php

namespace Micro\Plugin\Redis\Configuration;

use Micro\Framework\Kernel\Configuration\PluginRoutingKeyConfiguration;

class ClientOptionsConfiguration extends PluginRoutingKeyConfiguration implements ClientOptionsConfigurationInterface
{

    protected const CFG_SERIALIZER = 'REDIS_%s_OPT_SERIALIZER';
    protected const CFG_PREFIX     = 'REDIS_%s_OPT_PREFIX';
    protected const CFG_SCAN       = 'REDIS_%s_OPT_SCAN';

    public const PREFIX_DEFAULT     = '';
    public const SERIALIZER_DEFAULT = ClientOptionsConfigurationInterface::SERIALIZER_NONE;
    public const SCAN_DEFAULT       = '';

    /**
     * {@inheritDoc}
     */
    public function serializer(): string
    {
        return $this->get(self::CFG_SERIALIZER, self::SERIALIZER_DEFAULT);
    }

    /**
     * {@inheritDoc}
     */
    public function prefix(): string
    {
        return $this->get(self::CFG_PREFIX, self::PREFIX_DEFAULT);
    }

    /**
     * {@inheritDoc}
     */
    public function scan(): string
    {
        return $this->get(self::CFG_SCAN, self::SCAN_DEFAULT);
    }
}
