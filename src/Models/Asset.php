<?php

namespace Skcxck\Lunchmoney\Models;

/**
 * Class Asset
 * @package Skcxck\Lunchmoney\Models
 */
class Asset
{
    public int $id;

    public string $typeName;

    public ?string $subtypeName = null;

    public string $name;

    public string $balance;

    public string $balanceAsOf;

    public string $currency;

    public ?string $institutationName = null;

    public string $createdAt;
}