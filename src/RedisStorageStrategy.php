<?php

namespace Hizpark\ScopedStorageStrategy;

use Hizpark\ScopedStorageStrategy\Contracts\ScopedStorageStrategyContract;
use Redis;

class RedisStorageStrategy implements ScopedStorageStrategyContract
{
    public function __construct(private readonly Redis $redis)
    {
    }

    private function getHashKey(string $scopeId): string
    {
        return sprintf('%s:hash', $scopeId);  // 每個 scopeId 對應一個 hash 鍵
    }

    private function getFieldKey(string $key): string
    {
        return md5($key);  // 每個 key 使用 md5 來作為哈希表的字段
    }

    public function put(string $scopeId, string $key, string $value): void
    {
        // 使用哈希結構來存儲每個 scopeId 下的 key-value
        $this->redis->hSet($this->getHashKey($scopeId), $this->getFieldKey($key), $value);
    }

    public function get(string $scopeId, string $key): ?string
    {
        // 從指定的 scopeId 哈希中獲取對應的 key 的值
        return $this->redis->hGet($this->getHashKey($scopeId), $this->getFieldKey($key)) ?: null;
    }

    public function exists(string $scopeId, string $key): bool
    {
        // 檢查 scopeId 下的指定 key 是否存在
        return $this->redis->hExists($this->getHashKey($scopeId), $this->getFieldKey($key));
    }

    public function remove(string $scopeId, string $key): void
    {
        // 刪除 scopeId 下指定 key 的字段
        $this->redis->hDel($this->getHashKey($scopeId), $this->getFieldKey($key));
    }

    public function all(string $scopeId): array
    {
        // 獲取 scopeId 下所有的 key-value 資料
        $items = $this->redis->hGetAll($this->getHashKey($scopeId));

        // 確保 $items 不為空，再進行處理
        if (!$items) {
            return [];
        }

        // 將哈希表的字段名稱還原為原始的 key
        return array_map(function ($key) use ($items) {
            return ['key' => $key, 'value' => $items[$key]];
        }, array_keys($items));
    }

    public function empty(string $scopeId): bool
    {
        // 檢查 scopeId 下的哈希表是否為空
        return $this->redis->hLen($this->getHashKey($scopeId)) === 0;
    }

    public function clear(string $scopeId): void
    {
        // 刪除 scopeId 下的整個哈希表
        $this->redis->del($this->getHashKey($scopeId));
    }
}
