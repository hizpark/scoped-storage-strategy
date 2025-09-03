<?php

declare(strict_types=1);

namespace Hizpark\ScopedStorageStrategy\Session;

use Hizpark\ScopedStorageStrategy\ScopedStorageStrategyInterface;
use Hizpark\ScopedStorageStrategy\SessionInitializerInterface;

class SessionStorageStrategy implements ScopedStorageStrategyInterface
{
    private string $scope;

    private SessionInitializerInterface $initializer;

    public function __construct(string $scope, SessionInitializerInterface $initializer)
    {
        $this->scope       = $scope;
        $this->initializer = $initializer;
        $this->initializer->initialize();
    }

    private function getSessionKey(string $key): string
    {
        return sprintf('%s:%s', $this->scope, md5($key));
    }

    public function put(string $key, string $value): void
    {
        $_SESSION[$this->getSessionKey($key)] = $value;
    }

    public function get(string $key): ?string
    {
        /** @var string|null $value */
        $value = $_SESSION[$this->getSessionKey($key)] ?? null;

        return $value;
    }

    public function exists(string $key): bool
    {
        return isset($_SESSION[$this->getSessionKey($key)]);
    }

    public function remove(string $key): void
    {
        unset($_SESSION[$this->getSessionKey($key)]);
    }

    /**
     * @return array<int, array{key: string, value: string}>
     */
    public function all(): array
    {
        $prefix = sprintf('%s:', $this->scope);
        $items  = [];

        foreach ($_SESSION as $key => $value) {
            /** @var string $value */
            if (str_starts_with($key, $prefix)) {
                $items[] = [
                    'key'   => (string)$key,
                    'value' => $value,
                ];
            }
        }

        return $items;
    }

    public function empty(): bool
    {
        return empty($this->all());
    }

    public function clear(): void
    {
        $prefix = sprintf('%s:', $this->scope);

        foreach ($_SESSION as $key => $value) {
            if (str_starts_with($key, $prefix)) {
                unset($_SESSION[$key]);
            }
        }
    }
}
