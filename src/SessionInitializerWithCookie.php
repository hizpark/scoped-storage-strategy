<?php

namespace Hizpark\ScopedStorageStrategy;

use Hizpark\ScopedStorageStrategy\Contracts\SessionInitializerContract;

class SessionInitializerWithCookie implements SessionInitializerContract
{
    public function initialize(string $sessionId = null): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }
}
