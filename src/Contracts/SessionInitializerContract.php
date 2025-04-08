<?php

namespace Hizpark\ScopedStorageStrategy\Contracts;

interface SessionInitializerContract
{
    public function initialize(string $sessionId = null): void;
}
