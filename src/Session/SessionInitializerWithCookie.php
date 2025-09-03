<?php

declare(strict_types=1);

namespace Hizpark\ScopedStorageStrategy\Session;

use Hizpark\ScopedStorageStrategy\SessionInitializerInterface;

class SessionInitializerWithCookie implements SessionInitializerInterface
{
    public function initialize(?string $sessionId = null): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }
}
