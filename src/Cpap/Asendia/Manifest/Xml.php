<?php

namespace Cpap\Asendia\Manifest;

use SimpleXMLElement;

class Xml
{
    /**
     * @param Manifest $manifest
     * @param string $rootElementName
     *
     * @return SimpleXMLElement
     */
    public static function fromManifest(Manifest $manifest, $rootElementName = 'BwwManifest')
    {
        return static::fromProperties($manifest->getProperties(), $rootElementName);
    }

    /**
     * @param string $rootElementName
     *
     * @return SimpleXMLElement
     */
    public static function fromProperties(array $properties, $rootElementName = 'BwwManifest')
    {
        $xmlFormat = '<?xml version="1.0" encoding="ISO-8859-1" standalone="no" ?><%s />';
        $xml = sprintf($xmlFormat, $rootElementName);
        $xmlElement = new SimpleXMLElement($xml);
        $xmlElement = static::arrayToXml($properties, $xmlElement);

        return $xmlElement;
    }

    private static function arrayToXml($properties, SimpleXMLElement $xml) {
        foreach($properties as $key => $value) {
            if (is_array($value)) {
                $total_numeric_keys = count(array_filter(array_keys($value), function ($key) {
                    return is_numeric($key);
                }));

                $total_keys = count($value);

                if ($total_numeric_keys !== $total_keys) {
                    $child = $xml->addChild("$key");
                    foreach ($value as $childKey => $childValue) {
                        $child->addChild($childKey,htmlspecialchars("$childValue"));
                    }
                } else {
                    foreach ($value as $childValue) {
                        $child = $xml->addChild("$key");
                        static::arrayToXml($childValue, $child);
                    }
                }
            } else {
                $xml->addChild("$key",htmlspecialchars("$value"));
            }
        }

        return $xml;
    }
}
