<?php

namespace App\Service;

use App\Entity\Currency;
use App\Repository\CurrencyRepository;

class CurrencyService
{
    private CurrencyRepository $currencyRepository;

    public function __construct(
        CurrencyRepository $currencyRepository
    ) {
        $this->currencyRepository = $currencyRepository;
    }

    public function populateTheDb() 
    {
        $lastCurrency  = $this->currencyRepository->getLastCurrency();
        $lastSavedDate =  $lastCurrency ? date('d/m/Y', $lastCurrency->getDate()) : (new \DateTime('-1 month'))->format('d/m/Y');
        $now           = (new \DateTime())->format('d/m/Y');
        
        if ($now === $lastSavedDate) {
            return false;
        }

        $dailyRawXml = file_get_contents('http://www.cbr.ru/scripts/XML_daily.asp?date_req=' . $now);
        $dailyXml    = new \SimpleXMLElement($dailyRawXml);

        foreach ($dailyXml->Valute as $valute) {
            $dynamicRawXml = file_get_contents(
                sprintf('http://www.cbr.ru/scripts/XML_dynamic.asp?date_req1=%s&date_req2=%s&VAL_NM_RQ=%s', $lastSavedDate, $now, $valute->attributes()['Id'])
            );
            $dynamicXml = new \SimpleXMLElement($dynamicRawXml);

            foreach ($dynamicXml->Record as $record) {
                $date     = $record->attributes()['Date'];
                $value    = $record->Value;
                $currency = new Currency();
                $currency
                    ->setValuteID($valute->attributes()['Id'])
                    ->setNumCode($valute->NumCode)
                    ->setCharCode($valute->CharCode)
                    ->setName(iconv('UTF-8', 'Windows-1251', $valute->Name))
                    ->setValue($value)
                    ->setDate(strtotime($date));
                $this->currencyRepository->add($currency);
            }
        }

        return true;
    }
}