<?php

declare(strict_types=1);

namespace Hizpark\ScopedStorageStrategy\Tests;

use Hizpark\ScopedStorageStrategy\Redis\RedisStorageStrategy;
use PHPUnit\Framework\TestCase;
use Redis;

class RedisStorageStrategyTest extends TestCase
{
    private RedisStorageStrategy $strategy;

    protected function setUp(): void
    {
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        $this->strategy = new RedisStorageStrategy('upload:test', $redis);
        $this->strategy->clear();
    }

    public function testPutAndGet(): void
    {
        $this->strategy->put('key1', 'value1');
        $this->assertSame('value1', $this->strategy->get('key1'));
    }

    public function testExists(): void
    {
        $this->strategy->put('key2', 'value2');
        $this->assertTrue($this->strategy->exists('key2'));
    }

    public function testRemove(): void
    {
        $this->strategy->put('key3', 'value3');
        $this->strategy->remove('key3');
        $this->assertFalse($this->strategy->exists('key3'));
    }

    public function testAllAndEmptyAndClear(): void
    {
        $this->strategy->put('k1', 'v1');
        $this->strategy->put('k2', 'v2');

        $all = $this->strategy->all();
        $this->assertCount(2, $all);

        $this->assertFalse($this->strategy->empty());
        $this->strategy->clear();
        $this->assertTrue($this->strategy->empty());
    }
}
