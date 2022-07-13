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

    #[Route('/daily/date={date<\d{4}-\d{2}-\d{2}>}', name: 'by_date', methods: ['GET'])]
    public function getCurrencyListByDate(string $date): JsonResponse
    {
        $currencies = $this->currencyService->getCurrencyListByDate($date);

        try {
            $currencies = $this->currencyNormalizer->normalizeArrayOfCurrencies($currencies, true);
        } catch (\Exception $e) {
            $currencies = $e->getMessage();
        }

        return new JsonResponse($currencies);
    }

    #[Route('/period/datefrom={dateFrom<\d{4}-\d{2}-\d{2}>}&dateto={dateTo<\d{4}-\d{2}-\d{2}>}&valuteid={valuteId<R\w{5,6}>}', name: 'by_period', methods: ['GET'])]
    public function getCurrencyListByPeriod(string $dateFrom, string $dateTo, string $valuteId): JsonResponse
    {
        $currencies = $this->currencyService->getCurrencyListByPeriod($dateFrom, $dateTo, $valuteId);

        try {
            $currencies = $this->currencyNormalizer->normalizeArrayOfCurrencies($currencies, true);
        } catch (\Exception $e) {
            $currencies = $e->getMessage();
        }

        return new JsonResponse($currencies);
    }

    #[Route('/set', name: 'set', methods: ['POST'])]
    public function setCurrency(Request $request): JsonResponse 
    {
        $currency = $this->currencyService->save(
            $request->request->get('valuteId'), 
            $request->request->get('numCode'), 
            $request->request->get('charCode'), 
            $request->request->get('name'), 
            $request->request->get('value'), 
            $request->request->get('date')
        );

        if ($currency instanceof Currency) {
            $currency = $this->currencyNormalizer->normalize($currency);

            return new JsonResponse($currency);
        }

        return new JsonResponse(false);
    }

    #[Route('/update/{id<\d+>}', name: 'update', methods: ['PUT'])]
    public function updateCurrencyById(Request $request, int $id): JsonResponse
    {
        $result = $this->currencyService->update(
            $id,
            $request->request->get('valuteId'), 
            $request->request->get('numCode'), 
            $request->request->get('charCode'), 
            $request->request->get('name'), 
            $request->request->get('value'), 
            $request->request->get('date')
        );

        return new JsonResponse($result);
    }

    #[Route('/delete/{id<\d+>}', name: 'delete', methods: ['DELETE'])]
    public function deleteCurrencyById(int $id): JsonResponse
    {
        $result = $this->currencyService->delete($id);

        return new JsonResponse($result);
    }
}