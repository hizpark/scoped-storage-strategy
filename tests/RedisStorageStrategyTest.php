<?php

namespace Hizpark\ScopedStorageStrategy\Tests;

use Hizpark\ScopedStorageStrategy\RedisStorageStrategy;
use PHPUnit\Framework\TestCase;
use Redis;

class RedisStorageStrategyTest extends TestCase
{
    private RedisStorageStrategy $strategy;

    protected function setUp(): void
    {
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        $this->strategy = new RedisStorageStrategy($redis, 'upload:test');
        $this->strategy->clear('scope-redis');
    }

    public function testPutAndGet(): void
    {
        $this->strategy->put('scope-redis', 'key1', 'value1');
        $this->assertSame('value1', $this->strategy->get('scope-redis', 'key1'));
    }

    public function testExists(): void
    {
        $this->strategy->put('scope-redis', 'key2', 'value2');
        $this->assertTrue($this->strategy->exists('scope-redis', 'key2'));
    }

    public function testRemove(): void
    {
        $this->strategy->put('scope-redis', 'key3', 'value3');
        $this->strategy->remove('scope-redis', 'key3');
        $this->assertFalse($this->strategy->exists('scope-redis', 'key3'));
    }

    public function testAllAndEmptyAndClear(): void
    {
        $this->strategy->put('scope-redis', 'k1', 'v1');
        $this->strategy->put('scope-redis', 'k2', 'v2');

        $all = $this->strategy->all('scope-redis');
        $this->assertCount(2, $all);

        $this->assertFalse($this->strategy->empty('scope-redis'));
        $this->strategy->clear('scope-redis');
        $this->assertTrue($this->strategy->empty('scope-redis'));
    }
}
