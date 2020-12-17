<?php

namespace Skcxck\Lunchmoney\Commands;

use Exceptions\Operation\InvalidOperationException;
use Skcxck\Lunchmoney\Api\Lunchmoney;
use Skcxck\Lunchmoney\Models\AssetSubType;
use Skcxck\Lunchmoney\Models\AssetType;
use Skcxck\Lunchmoney\Transformers\AssetTransformer;

/**
 * Class ReceiveBrokerageAssets
 * @package Skcxck\Lunchmoney\Commands
 */
class ReceiveBrokerageAssets
{
    /**
     * @return array|\Skcxck\Lunchmoney\Models\Asset[]
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function run(): array
    {
        $lunchmoney = new Lunchmoney();

        $allAssets = $lunchmoney->getAllAssets();

        if ($allAssets === null) {
            throw new InvalidOperationException("Lunchmoney's response does not contain any processable data.");
        }

        $assets = array_filter(
            $lunchmoney->getAllAssets(),
            fn(array $item) => $item['type_name'] === AssetType::INVESTMENT && $item['subtype_name'] === AssetSubType::BROKERAGE
        );

        return array_map(
            fn(array $item) => AssetTransformer::denormalize($item),
            $assets
        );
    }
}