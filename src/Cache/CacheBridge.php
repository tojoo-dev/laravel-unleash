<?php

namespace Tojoo\Unleash\Cache;

use Illuminate\Support\Facades\Cache;
use Psr\SimpleCache\CacheInterface;

/**
 * Thanks to leo108 for the `SimpleCacheBridge.php` gist
 * https://gist.github.com/leo108/bd7559654c52000cc9774a80b072c629
 */
class CacheBridge implements CacheInterface
{
    /**
     * @param string $key
     * @param  mixed  $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return Cache::get($key, $default);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param  \DateInterval|\DateTimeInterface|int|null  $ttl
     * @return bool
     */
    public function set($key, $value, $ttl = null): bool
    {
        Cache::put($key, $value, $ttl);

        return true;
    }

    /**
     * @param $key
     * @return bool
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
     * @param iterable $keys
     * @param  mixed  $default
     * @return array
     */
    public function getMultiple($keys, $default = null): array
    {
        $values = Cache::many($keys);

        // Replace null values with the default value
        foreach ($keys as $key) {
            if (! isset($values[$key])) {
                $values[$key] = $default;
            }
        }

        return $values;
    }

    /**
     * @param iterable $values
     * @param  \DateInterval|\DateTimeInterface|int|null  $ttl
     * @return bool
     */
    public function setMultiple($values, $ttl = null): bool
    {
        Cache::putMany($values, $ttl);

        return true;
    }

    /**
     * @param array $keys
     */
    public function deleteMultiple($keys): bool
    {
        foreach ($keys as $key) {
            $this->delete($key);
        }

        return true;
    }

    /**
     * @param $key
     * @return bool
     */
    public function has($key): bool
    {
        return Cache::has($key);
    }
}
