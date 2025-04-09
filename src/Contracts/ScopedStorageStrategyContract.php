<?php

namespace Hizpark\ScopedStorageStrategy\Contracts;

interface ScopedStorageStrategyContract
{
    public function put(string $key, string $value): void;

    public function get(string $key): ?string;

    public function exists(string $key): bool;

    public function remove(string $key): void;

    public function all(): array;

    public function empty(): bool;

    public function clear(): void;
}
