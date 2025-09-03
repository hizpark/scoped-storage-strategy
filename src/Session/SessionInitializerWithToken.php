<?php

declare(strict_types=1);

namespace Hizpark\ScopedStorageStrategy\Session;

use Hizpark\ScopedStorageStrategy\SessionInitializerInterface;

class SessionInitializerWithToken implements SessionInitializerInterface
{
    private string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function initialize(?string $sessionId = null): void
    {
        ini_set('session.use_cookies', '0');
        ini_set('session.use_only_cookies', '0');

        // 使用 Token 初始化 session_id
        session_id($this->token);

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }
}
