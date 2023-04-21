<?php

namespace App\Service;

class CurrencyExchange
{
    public function fetchExchangeRates(string $base_currency, array $target_currencies): array
    {
        $response = [];
        foreach ($target_currencies as $target_currency) {
            $exchange_rate = $this->fetchSingleExchangeRate($base_currency, $target_currency);
            array_push($response, $exchange_rate);
        }
        return $response;
    }

    private function fetchSingleExchangeRate(string $base_currency, string $target_currency): array
    {
        return [
            $base_currency => $target_currency
        ];
    }
}
