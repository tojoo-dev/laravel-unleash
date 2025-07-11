<?php

namespace Tojoo\Unleash\Cache;

use Illuminate\Support\Facades\Cache;
use Psr\SimpleCache\CacheInterface;

/**
 * Thanks to leo108 for the `SimpleCacheBridge.php` gist
 * https://gist.github.com/leo108/bd7559654c52000cc9774a80b072c629
 * 
 * Compatible with PSR SimpleCache 1.0, 2.0, and 3.0
 */
class CacheBridge implements CacheInterface
{
    /**
     * Get cache value
     */
    public function get($key, $default = null)
    {
        return Cache::get($key, $default);
    }

    /**
     * Set cache value
     */
    public function set($key, $value, $ttl = null): bool
    {
        Cache::put($key, $value, $ttl);

        return true;
    }

    /**
     * Delete cache key
     */
    public function delete($key): bool
    {
        return Cache::forget($key);
    }

    /**
     * @return bool
     */
    public function clear(): bool
    {
        return Cache::flush();
    }

    /**
     * Get multiple cache values
     */
    public function getMultiple($keys, $default = null): iterable
    {
        $keysArray = is_array($keys) ? $keys : iterator_to_array($keys);
        $values = Cache::many($keysArray);

        // Replace null values with the default value
        foreach ($keysArray as $key) {
            if (! isset($values[$key])) {
                $values[$key] = $default;
            }
        }

        return $values;
    }

    /**
     * Set multiple cache values
     */
    public function setMultiple($values, $ttl = null): bool
    {
        $valuesArray = is_array($values) ? $values : iterator_to_array($values);
        Cache::putMany($valuesArray, $ttl);

        return true;
    }

    /**
     * Delete multiple cache keys
     */
    public function deleteMultiple($keys): bool
    {
        foreach ($keys as $key) {
            $this->delete($key);
        }

        return true;
    }

    /**
     * Check if cache key exists
     */
    public function has($key): bool
    {
        return Cache::has($key);
    }
}
