<?php

namespace App\Service;

use App\Entity\Currency;
use App\Repository\CurrencyRepository;
use App\Service\XmlService;

class CurrencyService
{
    private CurrencyRepository $currencyRepository;

    private XmlService $xmlService;

    public function __construct(
        CurrencyRepository $currencyRepository,
        XmlService $xmlService
    ) {
        $this->currencyRepository = $currencyRepository;
        $this->xmlService = $xmlService;
    }

    /**
     * @throws Exception
     */
    public function populateTheDb(): bool
    {
        $lastCurrency  = $this->currencyRepository->getLastCurrency();
        $lastSavedDate =  $lastCurrency ? date('d/m/Y', $lastCurrency->getDate()) : (new \DateTime('-1 month'))->format('d/m/Y');
        $now           = (new \DateTime())->format('d/m/Y');
        
        if ($now === $lastSavedDate) {
            return false;
        }

        try {
            $dailyRawXml = file_get_contents(sprintf('http://www.cbr.ru/scripts/XML_daily.asp?date_req=%s', $now));

            if ($dailyRawXml === false) {
                throw new \Exception('Неверный ответ сервера');
            }

            $dailyXml    = $this->xmlService->getXmlElement($dailyRawXml);
        } catch (\Exception $e) {
            throw $e;
        }

        foreach ($dailyXml->Valute as $valute) {
            $valuteId = (string) $valute->attributes()['ID'];

            try {
                $dynamicRawXml = file_get_contents(
                    sprintf('http://www.cbr.ru/scripts/XML_dynamic.asp?date_req1=%s&date_req2=%s&VAL_NM_RQ=%s', $lastSavedDate, $now, $valuteId)
                );

                if ($dynamicRawXml === false) {
                    throw new \Exception('Неверный ответ сервера');
                }

                $dynamicXml = $this->xmlService->getXmlElement($dynamicRawXml);
            } catch (\Exception $e) {
                throw $e;
            }

            foreach ($dynamicXml->Record as $record) {
                $date     = $record->attributes()['Date'];
                $value    = (float) str_replace(',', '.', $record->Value);
                $currency = new Currency();
                $currency
                    ->setValuteID($valuteId)
                    ->setNumCode((int) $valute->NumCode)
                    ->setCharCode((string) $valute->CharCode)
                    ->setName($valute->Nominal . ' ' . $valute->Name)
                    ->setValue($value)
                    ->setDate((int) strtotime($date));
                $this->currencyRepository->save($currency);
            }
        }

        $this->currencyRepository->endTransaction();

        return true;
    }

    public function getExchangeRatesByDate(string $date): ?array
    {
        $timestamp = strtotime($date);
        
        if (! $timestamp) {
            return null;
        }

        $rates = $this->currencyRepository->getExchangeRatesByDate($timestamp);

        return $rates;
    }

    public function save(string $valuteId, int $numCode, string $charCode, string $name, float $value, string $date): Currency
    {
        $currency = (new Currency)
            ->setValuteID($valuteId)
            ->setNumCode($numCode)
            ->setCharCode($charCode)
            ->setName($name)
            ->setValue($value)
            ->setDate(strtotime($date));
        $this->currencyRepository->save($currency, true);

        return $currency;
    }

    public function getExchangeRatesByPeriod(string $dateFrom, string $dateTo, string $valuteId): ?array
    {
        $timeFrom = strtotime($dateFrom);
        $timeTo   = strtotime($dateTo);
        
        if (! $timeFrom || ! $timeTo) {
            return null;
        }
        
        $rates = $this->currencyRepository->getExchangeRatesByPeriod($timeFrom, $timeTo, $valuteId);

        return $rates;
    }
}