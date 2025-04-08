<?php

namespace Hizpark\ScopedStorageStrategy\Tests;

use Hizpark\ScopedStorageStrategy\SessionInitializerWithCookie;
use Hizpark\ScopedStorageStrategy\SessionStorageStrategy;
use PHPUnit\Framework\TestCase;

class SessionStorageStrategyTest extends TestCase
{
    private SessionStorageStrategy $strategy;

    protected function setUp(): void
    {
        $_SESSION       = []; // isolate each test
        $initializer    = new SessionInitializerWithCookie();
        $this->strategy = new SessionStorageStrategy('upload:test', $initializer);
    }

    public function testPutAndGet(): void
    {
        $this->strategy->put('scope-session', 'key1', 'value1');
        $this->assertSame('value1', $this->strategy->get('scope-session', 'key1'));
    }

    public function testExists(): void
    {
        $this->strategy->put('scope-session', 'key2', 'value2');
        $this->assertTrue($this->strategy->exists('scope-session', 'key2'));
    }

    public function testRemove(): void
    {
        $this->strategy->put('scope-session', 'key3', 'value3');
        $this->strategy->remove('scope-session', 'key3');
        $this->assertFalse($this->strategy->exists('scope-session', 'key3'));
    }

    public function testAllAndEmptyAndClear(): void
    {
        $this->strategy->put('scope-session', 'k1', 'v1');
        $this->strategy->put('scope-session', 'k2', 'v2');

        $all = $this->strategy->all('scope-session');
        $this->assertCount(2, $all);

        $this->assertFalse($this->strategy->empty('scope-session'));
        $this->strategy->clear('scope-session');
        $this->assertTrue($this->strategy->empty('scope-session'));
    }
}
