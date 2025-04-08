<?php

namespace Hizpark\ScopedStorageStrategy;

use Hizpark\ScopedStorageStrategy\Contracts\ScopedStorageStrategyContract;
use Hizpark\ScopedStorageStrategy\Contracts\SessionInitializerContract;

class SessionStorageStrategy implements ScopedStorageStrategyContract
{
    private SessionInitializerContract $initializer;

    public function __construct(
        private readonly string    $domain,
        SessionInitializerContract $initializer
    ) {
        $this->initializer = $initializer;
        $this->initializer->initialize(); // 初始化 session 一次就好
    }

    private function getSessionKey(string $scopeId, string $key): string
    {
        return sprintf('%s:%s:%s', $this->domain, $scopeId, md5($key));
    }

    public function put(string $scopeId, string $key, string $value): void
    {
        $_SESSION[$this->getSessionKey($scopeId, $key)] = $value;
    }

    public function get(string $scopeId, string $key): ?string
    {
        return $_SESSION[$this->getSessionKey($scopeId, $key)] ?? null;
    }

    public function exists(string $scopeId, string $key): bool
    {
        return isset($_SESSION[$this->getSessionKey($scopeId, $key)]);
    }

    public function remove(string $scopeId, string $key): void
    {
        unset($_SESSION[$this->getSessionKey($scopeId, $key)]);
    }

    public function all(string $scopeId): array
    {
        $prefix = sprintf('%s:%s:', $this->domain, $scopeId);
        $items  = [];

        foreach ($_SESSION as $key => $value) {
            if (str_starts_with($key, $prefix)) {
                $items[] = ['key' => $key, 'value' => $value];
            }
        }

        return $items;
    }

    public function empty(string $scopeId): bool
    {
        return empty($this->all($scopeId));
    }

    public function clear(string $scopeId): void
    {
        $prefix = sprintf('%s:%s:', $this->domain, $scopeId);

        foreach ($_SESSION as $key => $value) {
            if (str_starts_with($key, $prefix)) {
                unset($_SESSION[$key]);
            }
        }
    }
}
