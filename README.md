# AP\Routing\Cache

[![MIT License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

Caching of an unchanging routing index is typically prepared on one computer and used on another.

## Installation

```bash
composer require ap-lib/routing-cache
```

## Features

- Interface for saving and loading arrays what include links to classes to full related code 
- Implementation for saving and loading arrays to a PHP file

## Requirements

- PHP 8.3 or higher

## Getting Started

```php
// make routing
$routing = new Hashmap();

// setup index
$index = $routing->getIndexMaker();

$index->addEndpoint(Method::GET, "/", new Endpoint(
    [MainController::class, "handlerRoot"]
));

$index->addEndpoint(Method::GET, "/hello", new Endpoint(
    [MainController::class, "handlerHelloName"]
));

// Set up the cache object (in this case, it's just a filename)
$cache = new PhpFileRoutingCache("data.php");

// Store the index in the cache
$cache->set($index);

// Retrieve the array to init routing index from the cache
$retrieved_array = $cache->get();

```
