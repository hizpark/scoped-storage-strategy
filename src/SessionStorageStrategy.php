<?php

namespace Hizpark\ScopedStorageStrategy;

use Hizpark\ScopedStorageStrategy\Contracts\ScopedStorageStrategyContract;
use Hizpark\ScopedStorageStrategy\Contracts\SessionInitializerContract;

class SessionStorageStrategy implements ScopedStorageStrategyContract
{
    private string $scope;
    private SessionInitializerContract $initializer;

    public function __construct(string $scope, SessionInitializerContract $initializer)
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
        return $_SESSION[$this->getSessionKey($key)] ?? null;
    }

    public function exists(string $key): bool
    {
        return isset($_SESSION[$this->getSessionKey($key)]);
    }

    public function remove(string $key): void
    {
        unset($_SESSION[$this->getSessionKey($key)]);
    }

    public function all(): array
    {
        $prefix = sprintf('%s:', $this->scope);
        $items  = [];

        foreach ($_SESSION as $key => $value) {
            if (str_starts_with($key, $prefix)) {
                $items[] = ['key' => $key, 'value' => $value];
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
