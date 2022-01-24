<?php

namespace Micro\Plugin\Redis\Configuration;

interface ClientOptionsConfigurationInterface
{
    const SERIALIZER_NONE = 'SERIALIZER_NONE';
    const SERIALIZER_PHP  = 'SERIALIZER_PHP';
    const SERIALIZER_IGBINARY = 'SERIALIZER_IGBINARY';
    const SERIALIZER_MSGPACK = 'SERIALIZER_MSGPACK';
    const SERIALIZER_JSON = 'SERIALIZER_JSON';

    const SCAN_NORETRY = 'SCAN_NORETRY';
    const SCAN_RETRY = 'SCAN_RETRY';
    const SCAN_PREFIX = 'SCAN_PREFIX';
    const SCAN_NOPREFIX = 'SCAN_NOPREFIX';

    /**
     * @return string
     */
    public function serializer(): string;

    /**
     * @return string
     */
    public function prefix(): string;

    /**
     * @return string
     */
    public function scan(): string;
}
