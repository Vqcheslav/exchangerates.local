<?php

namespace App\Normalizer;

use App\Entity\Currency;
use Exception;

class CurrencyNormalizer
{
    /**
     * @throws \Exception
     */
    public function normalize($currency): array
    {
        if ( ! $this->supportsNormalization($currency)) {
            throw new Exception('It is not a Currency object. Normalization is does not supported');
        }

        $data = [
            'id'        => $currency->getId(),
            'valute_id' => $currency->getValuteId(),
            'num_code'  => $currency->getNumCode(),
            'char_code' => $currency->getCharCode(),
            'name'      => $currency->getName(),
            'value'     => $currency->getValue(),
            'date'      => $currency->getDate(),
        ];

        return $data;
    }

    public function normalizeArrayOfCurrencyList(array $currencyList, bool $showErrors = false): array
    {
        $data = [];

        foreach ($currencyList as $currency) {
            if ($showErrors || $currency instanceof Currency) {
                $data[] = $this->normalize($currency);
            }
        }

        return $data;
    }

    public function supportsNormalization($data): bool
    {
        return $data instanceof Currency;
    }
}