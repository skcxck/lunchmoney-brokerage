<?php

namespace Skcxck\Lunchmoney\Api;

use Scheb\YahooFinanceApi\ApiClient as YahooClient;
use Scheb\YahooFinanceApi\ApiClientFactory;
use Scheb\YahooFinanceApi\Results\Quote;

/**
 * Class YahooFinance
 * @package Skcxck\Lunchmoney\Api
 */
class YahooFinance
{

    protected YahooClient $yahooClient;

    public function __construct()
    {
        $this->yahooClient = ApiClientFactory::createApiClient();
    }

    /**
     * @param string $quote
     * @return Quote
     */
    public function getQuote(string $quote): Quote
    {
        return $this->yahooClient->getQuote($quote);
    }
}