<?php

namespace App\Services;

use App\Models\Account;
use Illuminate\Support\Facades\Http;

/**
 * Class CurrencyService
 * @package App\Services
 */
class CurrencyService
{
    /**
     * @var string
     */
    const EURO_SYMBOL = 'EUR';

    /**
     * @var string
     */
    const USD_SYMBOL = 'USD';

    /**
     * @example https://exchangeratesapi.io
     * @var string
     */
    private $currencyApiURL = 'https://api.exchangeratesapi.io';

    /**
     * @param  string  $base
     * @param  array  $symbols
     * @return array
     */
    public function getCurrentCurrency(
        string $base = self::USD_SYMBOL,
        array $symbols = [self::EURO_SYMBOL]
    ): array {
        $symbolsString = implode(',', $symbols);

        return Http::get(
            "$this->currencyApiURL/latest?base=$base&symbols=$symbolsString"
        )->json();
    }

    /**
     * @param  float  $amount
     * @return float
     */
    public function getAmountInUSDInfo(Account $account, float $amount): array
    {
        $currency = $this->getCurrentCurrency()['rates'][self::EURO_SYMBOL];
        $result = ["value" => $amount, "current_currency" => $currency];

        if ($account->currency == Account::EURO_CURRENCY) {
            $result["value"] = $amount / $currency;
        }

        return $result;
    }
}
