# Scoped Storage Strategy

![License](https://img.shields.io/github/license/hizpark/scoped-storage-strategy)

A pluggable and namespace-aware storage abstraction for temporarily persisting key-value data during scoped user interactions.
This strategy supports session-based (cookie and token) and Redis-based implementations, and is designed to decouple application logic from underlying storage mechanisms.

Ideal for tracking transient states — such as validation progress, multistep workflows, or temporary metadata — using a consistent and replaceable strategy.

## Features

- 🍪 **Cookie-based PHP session**
- 🆔 **Token-based PHP session** (stateless API support)
- 🚀 **Redis storage** for shared, scalable scenarios
- 🔌 PSR-style interface for easy integration and extension
- ✅ Unified interface with `put`, `get`, `exists`, `remove`, `clear`, etc.

## Installation

```bash
composer require hizpark/scoped-storage-strategy
```

## Directory Structure

```txt
src/
├── ScopedStorageStrategyInterface.php
├── SessionInitializerInterface.php
├── Session/
│   ├── SessionStorageStrategy.php
│   ├── SessionInitializerWithCookie.php
│   └── SessionInitializerWithToken.php
└── Redis/
    └── RedisStorageStrategy.php
```

## Usage

### 1. SessionStorageStrategy with Cookie

```php
use Hizpark\ScopedStorageStrategy\SessionStorageStrategy;
use Hizpark\ScopedStorageStrategy\SessionInitializerWithCookie;

$initializer = new SessionInitializerWithCookie();
$strategy = new SessionStorageStrategy('scope-123', $initializer);

$strategy->put('demo-file-123', '/path/to/demo-file-123');
$value = $strategy->get('demo-file-123');
```

### 2. SessionStorageStrategy with Token (for stateless APIs)

```php
use Hizpark\ScopedStorageStrategy\SessionStorageStrategy;
use Hizpark\ScopedStorageStrategy\SessionInitializerWithToken;

$token = $_GET['token'] ?? ''; // or from Authorization header
$initializer = new SessionInitializerWithToken($token);
$strategy = new SessionStorageStrategy('scope-456', $initializer);

$strategy->put('demo-file-456', '/path/to/demo-file-456');
$value = $strategy->get('demo-file-456');
```

### 3. RedisStorageStrategy

```php
use Hizpark\ScopedStorageStrategy\RedisStorageStrategy;

$redis = new \Redis();
$redis->connect('127.0.0.1', 6379);

$strategy = new RedisStorageStrategy('scope-789', $redis);

$strategy->put('demo-file-789', '/path/to/demo-file-789');
$value = $strategy->get('demo-file-789');
```

## Interface

All strategies implement the following interface:

```php
namespace Hizpark\ScopedStorageStrategy;

interface ScopedStorageStrategyInterface
{
    public function put(string $key, string $value): void;
    public function get(string $key): ?string;
    public function exists(string $key): bool;
    public function remove(string $key): void;
    public function all(): ?array;
    public function empty(): bool;
    public function clear(): void;
}
```

And session-based strategies require a session initializer:

```php
namespace Hizpark\ScopedStorageStrategy;

interface SessionInitializerInterface
{
    public function initialize(): void;
}
```

---

## 📜 License

MIT License. See the [LICENSE](LICENSE) file for details.
