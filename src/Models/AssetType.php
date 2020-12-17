<?php

namespace Skcxck\Lunchmoney\Models;

/**
 * Class AssetType
 * @package Skcxck\Lunchmoney\Models
 */
class AssetType
{
    public const INVESTMENT = 'investment';

    public static function getAvailableValues(): array
    {
        return [
            static::INVESTMENT
        ];
    }

    public static function isValid(string $value): bool
    {
        return in_array($value, static::getAvailableValues());
    }
}