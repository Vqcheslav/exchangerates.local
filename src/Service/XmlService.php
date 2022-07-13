<?php

namespace App\Service;

class XmlService
{
    public function isXml(string $xmlString): bool
    {
        libxml_use_internal_errors(true);
        $doc = simplexml_load_string($xmlString);

        if (! $doc) {
            $errors = libxml_get_errors();

            if (count($errors)) {
                libxml_clear_errors();

                return false;
            }
        }

        return true;
    }

    /**
     * @throws Exception
     */
    public function getXmlElement(string $data)
    {
        if (! $this->isXml($data)) {
            throw new \Exception('Invalid XML');
        }

        return new \SimpleXMLElement($data);
    }
}