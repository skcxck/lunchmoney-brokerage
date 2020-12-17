<?php

namespace Skcxck\Lunchmoney\Commands;

use Carbon\Carbon;
use Exceptions\Operation\InvalidOperationException;
use Illuminate\Config\Repository as Config;
use Skcxck\Lunchmoney\Api\YahooFinance;
use Skcxck\Lunchmoney\Models\Asset;

/**
 * Class ProcessBrokerageAssets
 * @package Skcxck\Lunchmoney\Commands
 */
class ProcessBrokerageAssets
{
    protected YahooFinance $yahooFinance;

    protected ReceiveBrokerageAssets $receiveAssets;

    protected UpdateBrokerageAsset $updateAsset;

    protected Config $config;

    public function __construct()
    {
        $this->yahooFinance = new YahooFinance();
        $this->receiveAssets = new ReceiveBrokerageAssets();
        $this->updateAsset = new UpdateBrokerageAsset();
        $this->config = new Config(include __DIR__ . '/../../config/app.php');
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function run(): void
    {
        $assets = $this->receiveAssets->run();

        if (count($assets) === 0) {
            throw new InvalidOperationException('No eligible assets found.');
        }

        $brokerages = $this->config->get('brokerages', []);

        foreach ($brokerages as $name => $positions) {
            $result = array_filter($assets, fn(Asset $asset) => $asset->name == $name);
            /** @var Asset $asset */
            $asset = reset($result);

            if ($asset === null) {
                continue;
            }

            $quotes = array_map(
                fn($quote, $amount) => $this->yahooFinance->getQuote($quote)->getRegularMarketPrice() * $amount,
                array_keys($positions), $positions
            );

            $asset->balance = array_sum($quotes);
            $asset->balanceAsOf = Carbon::now()->toIso8601String();

            $this->updateAsset->run($asset);
        }
    }
}