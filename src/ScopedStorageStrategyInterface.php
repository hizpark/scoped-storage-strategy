<?php

declare(strict_types=1);

namespace Hizpark\ScopedStorageStrategy;

interface ScopedStorageStrategyInterface
{
    public function put(string $key, string $value): void;

    public function get(string $key): ?string;

    public function exists(string $key): bool;

    public function remove(string $key): void;

    /**
     * @return array<int, array{key: string, value: string}>
     */
    public function all(): array;

    public function empty(): bool;

    public function clear(): void;
}
