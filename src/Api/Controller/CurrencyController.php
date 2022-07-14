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
        $currencyList = $this
            ->currencyService
            ->getCurrencyListByDate($date);

        try {
            $currencyList = $this
                ->currencyNormalizer
                ->normalizeArrayOfcurrencyList($currencyList, true);
        } catch (\Exception) {
            return new JsonResponse('Invalid request', 404);
        }

        return new JsonResponse($currencyList, 200);
    }

    #[Route('/period/date_from={dateFrom<\d{4}-\d{2}-\d{2}>}&date_to={dateTo<\d{4}-\d{2}-\d{2}>}&valute_id={valuteId<R\w{5,6}>}', name: 'by_period', methods: ['GET'])]
    public function getCurrencyListByPeriod(string $dateFrom, string $dateTo, string $valuteId): JsonResponse
    {
        $currencyList = $this
            ->currencyService
            ->getCurrencyListByPeriod($dateFrom, $dateTo, $valuteId);

        try {
            $currencyList = $this
                ->currencyNormalizer
                ->normalizeArrayOfcurrencyList($currencyList, true);
        } catch (\Exception) {
            return new JsonResponse('Invalid request', 404);
        }

        return new JsonResponse($currencyList, 200);
    }

    #[Route('/set', name: 'set', methods: ['POST'])]
    public function setCurrency(Request $request): JsonResponse 
    {
        $currency = $this->currencyService->save(
            $request->request->get('valute_id'),
            $request->request->get('num_code'),
            $request->request->get('char_code'),
            $request->request->get('name'),
            $request->request->get('value'),
            $request->request->get('date')
        );

        if ($currency instanceof Currency) {
            $currency = $this
                ->currencyNormalizer
                ->normalize($currency);

            return new JsonResponse($currency, 200);
        }

        return new JsonResponse('Invalid request', 404);
    }

    #[Route('/put/{id<\d+>}', name: 'put', methods: ['PUT'])]
    public function putCurrencyById(Request $request, int $id): JsonResponse
    {
        $currency = $this->currencyService->put(
            $id,
            $request->request->get('valute_id'),
            $request->request->get('num_code'),
            $request->request->get('char_code'),
            $request->request->get('name'),
            $request->request->get('value'),
            $request->request->get('date')
        );

        if ($currency instanceof Currency) {
            $currency = $this
                ->currencyNormalizer
                ->normalize($currency);

            if ($currency['id'] === $id) {
                return new JsonResponse($currency, 200);
            }

            return new JsonResponse($currency, 201);
        }

        return new JsonResponse('Invalid request', 404);
    }

    #[Route('/delete/{id<\d+>}', name: 'delete', methods: ['DELETE'])]
    public function deleteCurrencyById(int $id): JsonResponse
    {
        $result = $this
            ->currencyService
            ->delete($id);

        if ($result) {
            return new JsonResponse('File deleted', 200);
        }

        return new JsonResponse('Invalid request', 404);
    }
}