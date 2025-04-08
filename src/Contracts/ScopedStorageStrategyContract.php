<?php

namespace Hizpark\ScopedStorageStrategy\Contracts;

interface ScopedStorageStrategyContract
{
    public function put(string $scopeId, string $key, string $value): void;

    public function get(string $scopeId, string $key): ?string;

    public function exists(string $scopeId, string $key): bool;

    public function remove(string $scopeId, string $key): void;

    public function all(string $scopeId): array;

    public function empty(string $scopeId): bool;

    public function clear(string $scopeId): void;
}
