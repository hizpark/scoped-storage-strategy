<?php

declare(strict_types=1);

namespace Hizpark\ScopedStorageStrategy\Redis;

use Hizpark\ScopedStorageStrategy\ScopedStorageStrategyInterface;
use Redis;

class RedisStorageStrategy implements ScopedStorageStrategyInterface
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
        /** @var string|false $value */
        $value = $this->redis->hGet($this->getHashKey(), $this->getFieldKey($key));

        return $value === false ? null : $value;
    }

    public function exists(string $key): bool
    {
        return $this->redis->hExists($this->getHashKey(), $this->getFieldKey($key));
    }

    public function remove(string $key): void
    {
        $this->redis->hDel($this->getHashKey(), $this->getFieldKey($key));
    }

    /**
     * @return array<int, array{key: string, value: string}>
     */
    public function all(): array
    {
        /** @var array<string, string> $items */
        $items = $this->redis->hGetAll($this->getHashKey());

        if (!$items) {
            return [];
        }

        return array_map(function (string $key) use ($items) {
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
