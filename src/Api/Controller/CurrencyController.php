<?php

namespace App\Api\Controller;

use App\Service\CurrencyService;
use App\Normalizer\CurrencyNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/currency', name: 'api_currency_')]
class CurrencyController extends AbstractController
{
    private CurrencyService $currencyService;

    private CurrencyNormalizer $currencyNormalizer;

    public function __construct(
        CurrencyService $currencyService,
        CurrencyNormalizer $currencyNormalizer
    ) {
        $this->currencyService    = $currencyService;
        $this->currencyNormalizer = $currencyNormalizer;
    }

    #[Route('/daily/date={date<\d{4}-\d{2}-\d{2}>}', name: 'by_date')]
    public function getExchangeRatesByDate(string $date): JsonResponse
    {
        $currencies = $this->currencyService->getExchangeRatesByDate($date);
        try {
            $currencies = $this->currencyNormalizer->normalizeArrayOfCurrencies($currencies, true);
        } catch (\Exception $e) {
            $currencies = $e->getMessage();
        }

        return new JsonResponse($currencies);
    }
}