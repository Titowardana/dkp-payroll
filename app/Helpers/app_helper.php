<?php

if (! function_exists('rupiah')) {
    function rupiah(int|float|string $value): string
    {
        return 'Rp ' . number_format((float) $value, 0, ',', '.');
    }
}

if (! function_exists('status_badge_class')) {
    function status_badge_class(?string $status): string
    {
        return match ($status) {
            'draft' => 'warning',
            'verified' => 'info',
            'approved' => 'primary',
            'paid' => 'success',
            'rejected' => 'danger',
            default => 'secondary',
        };
    }
}
