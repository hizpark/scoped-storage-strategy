# Scoped Storage Strategy

![License](https://img.shields.io/github/license/hizpark/scoped-storage-strategy)

A pluggable and namespace-aware storage abstraction for temporarily persisting key-value data during scoped user interactions.
This strategy supports session-based (cookie and token) and Redis-based implementations, and is designed to decouple application logic from underlying storage mechanisms.

Ideal for tracking transient states — such as validation progress, multi-step workflows, or temporary metadata — using a consistent and replaceable strategy.

## Features

- 🍪 **Cookie-based PHP session**
- 🆔 **Token-based PHP session** (stateless API support)
- 🚀 **Redis storage** for shared, scalable scenarios
- 🔌 PSR-style contract for easy integration and extension
- ✅ Unified interface with `put`, `get`, `exists`, `remove`, `clear`, etc.

## Installation

```bash
composer require hizpark/scoped-storage-strategy
```

## Usage

### 1. SessionStorageStrategy with Cookie

```php
use ScopedStorageStrategy\SessionStorageStrategy;
use ScopedStorageStrategy\SessionInitializerWithCookie;

$initializer = new SessionInitializerWithCookie();
$strategy = new SessionStorageStrategy('upload', $initializer);

$strategy->put('scope-id-123', '/path/to/file', 'uploaded');
$value = $strategy->get('scope-id-123', '/path/to/file');
```

### 2. SessionStorageStrategy with Token (for stateless APIs)

```php
use ScopedStorageStrategy\SessionStorageStrategy;
use ScopedStorageStrategy\SessionInitializerWithToken;

$token = $_GET['token'] ?? ''; // or from Authorization header
$initializer = new SessionInitializerWithToken($token);
$strategy = new SessionStorageStrategy('upload', $initializer);

$strategy->put('scope-id-456', '/path/to/file', 'uploaded');
```

### 3. RedisStorageStrategy

```php
use ScopedStorageStrategy\RedisStorageStrategy;

$redis = new \Redis();
$redis->connect('127.0.0.1', 6379);

$strategy = new RedisStorageStrategy($redis, 'upload');

$strategy->put('scope-id-789', '/file.jpg', 'uploaded');
```

## Contract

All strategies implement the following interface:

```php
namespace ScopedStorageStrategy\Contracts;

interface ScopedStorageStrategyContract
{
public function put(string $scopeId, string $key, string $value): void;
public function get(string $scopeId, string $key): ?string;
public function exists(string $scopeId, string $key): bool;
public function remove(string $scopeId, string $key): void;
public function all(string $scopeId): ?array;
public function empty(string $scopeId): bool;
public function clear(string $scopeId): void;
}
```

And session-based strategies require a session initializer:

```php
namespace ScopedStorageStrategy\Contracts;

interface SessionInitializerContract
{
public function initialize(): void;
}
```

## Directory Structure

```txt
src/
├── Contracts/
│   ├── ScopedStorageStrategyContract.php
│   └── SessionInitializerContract.php
├── RedisStorageStrategy.php
├── SessionInitializerWithCookie.php
├── SessionInitializerWithToken.php
└── SessionStorageStrategy.php
```
