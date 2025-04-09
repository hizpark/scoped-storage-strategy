<?php

namespace Hizpark\ScopedStorageStrategy;

use Hizpark\ScopedStorageStrategy\Contracts\ScopedStorageStrategyContract;
use Redis;

class RedisStorageStrategy implements ScopedStorageStrategyContract
{
    private string $scope;
    private Redis $redis;

    public function __construct(string $scope, Redis $redis)
    {
        $this->scope = $scope;
        $this->redis = $redis;
    }

    private function getHashKey(): string
    {
        return sprintf('%s:hash', $this->scope);
    }

    private function getFieldKey(string $key): string
    {
        return md5($key);
    }

    public function put(string $key, string $value): void
    {
        $this->redis->hSet($this->getHashKey(), $this->getFieldKey($key), $value);
    }

    public function get(string $key): ?string
    {
        return $this->redis->hGet($this->getHashKey(), $this->getFieldKey($key)) ?: null;
    }

    public function exists(string $key): bool
    {
        return $this->redis->hExists($this->getHashKey(), $this->getFieldKey($key));
    }

    public function remove(string $key): void
    {
        $this->redis->hDel($this->getHashKey(), $this->getFieldKey($key));
    }

    public function all(): array
    {
        $items = $this->redis->hGetAll($this->getHashKey());

        if (!$items) {
            return [];
        }

        return array_map(function ($key) use ($items) {
            return ['key' => $key, 'value' => $items[$key]];
        }, array_keys($items));
    }

    public function empty(): bool
    {
        return $this->redis->hLen($this->getHashKey()) === 0;
    }

    public function clear(): void
    {
        $this->redis->del($this->getHashKey());
    }
}
