<?php declare(strict_types=1);

namespace AP\Routing\Cache;

use AP\Routing\Routing\Routing\IndexInterface;
use Throwable;
use UnexpectedValueException;

interface RoutingCacheInterface
{
    public function get(): array;

    /**
     * @param IndexInterface $index
     * @return void
     * @throws UnexpectedValueException if not found
     * @throws Throwable all other errors
     */
    public function set(IndexInterface $index): void;
}