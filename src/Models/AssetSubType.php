<?php

namespace Skcxck\Lunchmoney\Models;

/**
 * Class AssetSubType
 * @package Skcxck\Lunchmoney\Models
 */
class AssetSubType
{
    public const BROKERAGE = 'brokerage';

    public static function getAvailableValues(): array
    {
        return [
            static::BROKERAGE
        ];
    }

    public static function isValid(string $value): bool
    {
        return in_array($value, static::getAvailableValues());
    }
}