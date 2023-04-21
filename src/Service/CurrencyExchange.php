<?php

namespace App\Service;

use GuzzleHttp;
use App\Entity\CurrencyRates;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

class CurrencyExchange
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }
    public function fetchExchangeRates(string $base_currency, array $target_currencies): array
    {
        $response = [];
        foreach ($target_currencies as $target_currency) {
            $exchange_rate = $this->fetchSingleExchangeRate($base_currency, $target_currency);
            array_push($response, $exchange_rate);
        }
        return $response;
    }

    private function fetchSingleExchangeRate(string $base_currency, string $target_currency): self
    {
        $repository = $this->entityManager->getRepository(CurrencyRates::class);

        $base_currency = strtoupper($base_currency);
        $target_currency = strtoupper($target_currency);
        $key = $base_currency . ":" . $target_currency;

        $cache = new FilesystemAdapter();

        $currency_rate = $cache->get($key, function (ItemInterface $item) use ($base_currency, $target_currency, $repository) {
            echo 'Miss <br>';
            return $repository->findOneBy([
                'base_currency' => $base_currency,
                'target_currency' => $target_currency,
            ]);
        });
        return $currency_rate->getTargetCurrencyRate();
    }

    public function populateExchangeRates(string $base_currency, array $target_currencies)
    {
        $client = new GuzzleHttp\Client(['base_uri' => 'https://foo.com/api/']);
        $response = $client->request('GET', 'test');

        foreach ($target_currencies as $target_currency) {
            $currency_rate = new CurrencyRates();
            $currency_rate->setBaseCurrency($base_currency);
            $currency_rate->setTargetCurrency($target_currency);
            $currency_rate->setTargetCurrencyRate(0);
        }
    }
}
