<?php

declare(strict_types=1);

namespace Hizpark\ScopedStorageStrategy;

interface SessionInitializerInterface
{
    public function initialize(?string $sessionId = null): void;
}
