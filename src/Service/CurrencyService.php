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
        $this->xmlService         = $xmlService;
    }

    /**
     * @throws Exception
     */
    public function populateTheDb(): bool
    {
        $lastCurrency = $this
            ->currencyRepository
            ->getLastCurrency();
        $lastSavedDate = $lastCurrency ? date('d/m/Y', $lastCurrency->getDate()) : (new \DateTime('-1 month'))->format('d/m/Y');
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
                    sprintf(
                        'http://www.cbr.ru/scripts/XML_dynamic.asp?date_req1=%s&date_req2=%s&VAL_NM_RQ=%s', 
                        $lastSavedDate, 
                        $now, 
                        $valuteId
                    )
                );

                if ($dynamicRawXml === false) {
                    throw new \Exception('Неверный ответ сервера');
                }

                $dynamicXml = $this
                    ->xmlService
                    ->getXmlElement($dynamicRawXml);
            } catch (\Exception $e) {
                throw $e;
            }

            foreach ($dynamicXml->Record as $record) {
                $date     = $record->attributes()['Date'];
                $value    = (float) str_replace(',', '.', $record->Value);
                $currency = (new Currency())
                    ->setValuteID($valuteId)
                    ->setNumCode((string) $valute->NumCode)
                    ->setCharCode((string) $valute->CharCode)
                    ->setName($valute->Nominal . ' ' . $valute->Name)
                    ->setValue($value)
                    ->setDate((int) strtotime($date));
                $this
                    ->currencyRepository
                    ->save($currency, false);
            }
        }

        $this->currencyRepository->flush();

        return true;
    }

    public function getCurrencyListByDate(string $date): ?array
    {
        $timestamp = strtotime($date);
        
        if (! $timestamp) {
            return null;
        }

        $rates = $this
            ->currencyRepository
            ->getCurrencyListByDate($timestamp);

        return $rates;
    }

    public function getCurrencyListByPeriod(string $dateFrom, string $dateTo, string $valuteId): ?array
    {
        $timeFrom = strtotime($dateFrom);
        $timeTo   = strtotime($dateTo);
        
        if (! $timeFrom || ! $timeTo) {
            return null;
        }
        
        $rates = $this
            ->currencyRepository
            ->getCurrencyListByPeriod($timeFrom, $timeTo, $valuteId);

        return $rates;
    }

    public function save(
        string $valuteId, 
        string $numCode, 
        string $charCode, 
        string $name, 
        float $value, 
        string $date
    ): ?Currency {
        if (! $this->checkArgsForCurrency($valuteId, $numCode, $charCode, $name, $value, $date)) {
            return null;
        }

        $currency = (new Currency)
            ->setValuteID($valuteId)
            ->setNumCode($numCode)
            ->setCharCode($charCode)
            ->setName($name)
            ->setValue($value)
            ->setDate(strtotime($date));
        $this
            ->currencyRepository
            ->save($currency);

        return $currency;
    }

    public function update(
        int $id,
        string $valuteId, 
        string $numCode, 
        string $charCode, 
        string $name, 
        float $value, 
        string $date
    ) {
        if (! $this->checkArgsForCurrency($valuteId, $numCode, $charCode, $name, $value, $date)) {
            return null;
        }

        $currency = $this
            ->currencyRepository
            ->find($id);
        
        if (! ($currency instanceof Currency)) {
            return null;
        }

        $currency
            ->setValuteID($valuteId)
            ->setNumCode($numCode)
            ->setCharCode($charCode)
            ->setName($name)
            ->setValue($value)
            ->setDate(strtotime($date));
        $this
            ->currencyRepository
            ->save($currency);

        return $currency;
    }

    public function delete(int $id): bool
    {
        $currency = $this
            ->currencyRepository
            ->find($id);

        if ($currency instanceof Currency){
            $this
                ->currencyRepository
                ->remove($currency);

            return true;
        }

        return false;
    }

    private function checkArgsForCurrency(
        string $valuteId, 
        string $numCode, 
        string $charCode, 
        string $name, 
        float $value, 
        string $date
    ) {
        $reg         = '/(R\w{5,6})&(\d{3})&([[:alpha:]]+)&(\w+.+\w+)&(\d+\.\d+)&(\d{4}-\d{2}-\d{2})/';
        $arrayOfArgs = [$valuteId, $numCode, $charCode, $name, $value, $date];

        if (preg_match($reg, implode('&', $arrayOfArgs)) === 1) {
            return true;
        }

        return false;
    }
}