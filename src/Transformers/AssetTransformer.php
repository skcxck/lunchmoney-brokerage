<?php

namespace Skcxck\Lunchmoney\Transformers;

use Skcxck\Lunchmoney\Models\Asset;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * Class AssetTransformer
 * @package Skcxck\Lunchmoney\Transformers
 */
class AssetTransformer
{
    /**
     * @param Asset $asset
     * @return array
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public static function normalize(Asset $asset): array
    {
        $normalize = new ObjectNormalizer(null, new CamelCaseToSnakeCaseNameConverter());

        return $normalize->normalize($asset, 'array');
    }

    /**
     * @param array $asset
     * @return Asset|object
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public static function denormalize(array $asset): Asset
    {
        $normalize = new ObjectNormalizer(null, new CamelCaseToSnakeCaseNameConverter());

        return $normalize->denormalize($asset, Asset::class);
    }
}