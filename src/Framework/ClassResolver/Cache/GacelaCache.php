<?php

declare(strict_types=1);

namespace Gacela\Framework\ClassResolver\Cache;

use Gacela\Framework\Config\ConfigInterface;

final class GacelaCache
{
    public const KEY_ENABLED = 'gacela-cache-enabled';
    public const DEFAULT_ENABLED_VALUE = true;
    public const DEFAULT_DIRECTORY_VALUE = '.gacela/cache';

    private ConfigInterface $config;

    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    public function isProjectCacheEnabled(): bool
    {
        if (!$this->config->getSetupGacela()->isCacheEnabled()) {
            return false;
        }

        return (bool) $this->config->get(self::KEY_ENABLED, self::DEFAULT_ENABLED_VALUE);
    }
}
