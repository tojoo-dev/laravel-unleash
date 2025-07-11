<?php

namespace Tojoo\Unleash\Cache;

use Tojoo\Unleash\Interfaces\UnleashCacheHandlerInterface;

class CacheHandler implements UnleashCacheHandlerInterface
{
    /**
     * @return CacheBridge
     */
    public function init(): CacheBridge
    {
        return new CacheBridge();
    }
}
