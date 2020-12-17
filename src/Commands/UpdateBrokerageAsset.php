<?php

namespace Skcxck\Lunchmoney\Commands;

use Skcxck\Lunchmoney\Api\Lunchmoney;
use Skcxck\Lunchmoney\Models\Asset;
use Skcxck\Lunchmoney\Transformers\AssetTransformer;

/**
 * Class UpdateBrokerageAsset
 * @package Skcxck\Lunchmoney\Commands
 */
class UpdateBrokerageAsset
{
    protected Lunchmoney $lunchmoney;

    public function __construct()
    {
        $this->lunchmoney = new Lunchmoney();
    }

    /**
     * @param Asset $asset
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function run(Asset $asset): array
    {
        $formatted = AssetTransformer::normalize($asset);

        return $this->lunchmoney->updateAsset($formatted);
    }
}