<?php

namespace App\Controller;

use App\Service\CurrencyExchange;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CurrencyExchangeController extends AbstractController
{

    public function __construct(
        private CurrencyExchange $currencyExchange,
    ) {
    }

    #[Route('/api/exchange-rates', name: 'get_exchange_rates', methods: ["GET"])]
    public function get_exchange_rates(Request $request): JsonResponse
    {

        // dump($request->query->get('base_currency'));
        // dump($request->query->get('target_currencies'));
        $base_currency = $request->query->get('base_currency');
        $target_currencies = $request->query->get('target_currencies');
        $target_currencies = explode(",", $target_currencies);

        $response = $this->currencyExchange->fetchExchangeRates($base_currency, $target_currencies);
        return $this->json([
            'message' => $base_currency,
            'path' => 'src/Controller/CurrencyExchangeController.php',
            'data' => $target_currencies,
            'response' => $response
        ]);
    }
}
