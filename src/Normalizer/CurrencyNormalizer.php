<?php

namespace App\Normalizer;

use App\Entity\Currency;

class CurrencyNormalizer
{
    public function normalize($currency): array
    {
        if (! $this->supportsNormalization($currency)) {
            throw new \Exception('It is not a Currency object. Normalization is does not supported');
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

    public function normalizeArrayOfCurrencies(array $currencies, bool $showErrors = false): array
    {
        $data = [];

        foreach ($currencies as $currency) {
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