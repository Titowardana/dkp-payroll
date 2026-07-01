<?php

namespace Composer\Pcre;

/**
 * Minimal replacement for composer/pcre Preg helper used by PhpSpreadsheet.
 * This wraps the built-in preg_* functions so the library can run
 * without requiring the full Composer dependency.
 */
class Preg
{
    public static function isMatch(string $pattern, string $subject, ?array &$matches = null, int $flags = 0, int $offset = 0): bool
    {
        $result = preg_match($pattern, $subject, $matches, $flags, $offset);

        return $result === 1;
    }

    public static function replace($pattern, $replacement, $subject, int $limit = -1, ?int &$count = null)
    {
        return preg_replace($pattern, $replacement, $subject, $limit, $count);
    }

    public static function replaceCallback($pattern, callable $callback, $subject, int $limit = -1, ?int &$count = null, int $flags = 0)
    {
        return preg_replace_callback($pattern, $callback, $subject, $limit, $count, $flags);
    }

    public static function split(string $pattern, string $subject, int $limit = -1, int $flags = 0): array
    {
        $result = preg_split($pattern, $subject, $limit, $flags);

        return $result === false ? [] : $result;
    }

    public static function matchAllWithOffsets(string $pattern, string $subject, ?array &$matches = null, int $flags = 0, int $offset = 0): int
    {
        // Use PREG_OFFSET_CAPTURE so offsets are returned as expected.
        return preg_match_all($pattern, $subject, $matches, $flags | PREG_OFFSET_CAPTURE, $offset);
    }
}

