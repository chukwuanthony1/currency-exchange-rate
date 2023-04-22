<?php

namespace App\Service;

use App\Entity\CurrencyExchangeRates;
use GuzzleHttp\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class CurrencyExchange
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private string $open_exchange_rate_app_id,
        private CacheInterface $currencyExchangeCache
    ) {
    }
    public function fetchExchangeRates(string $base_currency, array $target_currencies): array
    {
        $response = array();
        foreach ($target_currencies as $target_currency) {
            $exchange_rate = $this->fetchSingleExchangeRate($base_currency, $target_currency);
            $response[$target_currency] =  $exchange_rate;
        }
        return $response;
    }

    private function fetchSingleExchangeRate(string $base_currency, string $target_currency): string
    {
        $repository = $this->entityManager->getRepository(CurrencyExchangeRates::class);

        $base_currency = strtoupper($base_currency);
        $target_currency = strtoupper($target_currency);
        $key = $base_currency . "=>" . $target_currency;



        $currency_rate = $this->currencyExchangeCache->get($key, function (ItemInterface $item) use ($base_currency, $target_currency, $repository) {
            $item->expiresAfter(86400);
            return $repository->findOneBy([
                'base_currency' => $base_currency,
                'target_currency' => $target_currency,
            ]);
        });
        return $currency_rate->getExchangeRate();
    }

    public function populateExchangeRates(string $base_currency, array $target_currencies)
    {
        $repository = $this->entityManager->getRepository(CurrencyExchangeRates::class);
        $client = new Client();
        $response = $client->get("https://openexchangerates.org/api/latest.json?app_id=" . $this->open_exchange_rate_app_id . "&base=" . $base_currency . "", [
            'headers' => [
                'Accept'     => 'application/json',
            ]
        ]);
        // dump("https://data.fixer.io/api/latest?access_key=" . $this->fixer_io_api_key . "");
        $body = $response->getBody();
        $body = json_decode($body);

        foreach ($body->rates as $key => $value) {
            $exists =  $repository->findOneBy([
                'base_currency' => $base_currency,
                'target_currency' => $key,
            ]);

            if (!$exists) {
                $currency_rate = new CurrencyExchangeRates();
                $currency_rate->setBaseCurrency($base_currency);
                $currency_rate->setTargetCurrency($key);
                $currency_rate->setExchangeRate($value);
                $this->entityManager->persist($currency_rate);
            } else {
                $exists->setExchangeRate($value);
            }
        }
        $this->entityManager->flush();

        foreach ($target_currencies as $target_currency) {
            $key = $base_currency . "=>" . $target_currency;
            $currency_rate = $this->currencyExchangeCache->get($key, function (ItemInterface $item) use ($base_currency, $target_currency, $repository) {
                $item->expiresAfter(86400);
                return $repository->findOneBy([
                    'base_currency' => $base_currency,
                    'target_currency' => $target_currency,
                ]);
            });
            dump($currency_rate->getExchangeRate());
        }
    }
}
