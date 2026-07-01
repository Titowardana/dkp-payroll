<?php

namespace Psr\SimpleCache;

/**
 * Minimal PSR-16 CacheInterface stub required by PhpSpreadsheet.
 *
 * Ini bukan implementasi cache penuh, hanya interface supaya
 * type-hint PhpSpreadsheet tidak error saat library PSR aslinya
 * tidak di-install via Composer.
 */
interface CacheInterface
{
    public function get(string $key, mixed $default = null): mixed;

    public function set(string $key, mixed $value, null|int|\DateInterval $ttl = null): bool;

    public function delete(string $key): bool;

    public function clear(): bool;

    public function getMultiple(iterable $keys, mixed $default = null): iterable;

    public function setMultiple(iterable $values, null|int|\DateInterval $ttl = null): bool;

    public function deleteMultiple(iterable $keys): bool;

    public function has(string $key): bool;
}

