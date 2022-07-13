<?php

namespace App\Api\Controller;

use App\Entity\Currency;
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

    #[Route('/period/datefrom={dateFrom<\d{4}-\d{2}-\d{2}>}&dateto={dateTo<\d{4}-\d{2}-\d{2}>}&valuteid={valuteId<R\d{5}>}', name: 'by_period')]
    public function getExchangeRatesByPeriod(string $dateFrom, string $dateTo, string $valuteId): JsonResponse
    {
        $currencies = $this->currencyService->getExchangeRatesByPeriod($dateFrom, $dateTo, $valuteId);

        try {
            $currencies = $this->currencyNormalizer->normalizeArrayOfCurrencies($currencies, true);
        } catch (\Exception $e) {
            $currencies = $e->getMessage();
        }

        return new JsonResponse($currencies);
    }

    #[Route('/set/valuteid={valuteId<R\d{5}>}&numcode={numCode<\d{3}>}&charcode={charCode<[[:alpha:]]+>}&name={name<\w+.+\w+>}&value={value<\d+\.\d+>}&date={date<\d{4}-\d{2}-\d{2}>}', name: 'set')]
    public function setExchangeRate(
        string $valuteId,
        int $numCode,
        string $charCode,
        string $name,
        float $value,
        string $date
    ): JsonResponse {
        $currency = $this->currencyService->save($valuteId, $numCode, $charCode, $name, $value, $date);

        if ($currency instanceof Currency) {
            $currency = $this->currencyNormalizer->normalize($currency);

            return new JsonResponse($currency);
        }

        return new JsonResponse(false);
    }

    #[Route('/delete/id={id<\d+>}', name: 'delete')]
    public function deleteExchangeRateById(int $id): JsonResponse
    {
        $result = $this->currencyService->delete($id);

        return new JsonResponse($result);
    }
}