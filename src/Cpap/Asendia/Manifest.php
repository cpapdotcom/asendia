<?php

namespace Cpap\Asendia;

use DateTime;

class Manifest
{
    /**
     * @param string $accountNumber
     * @param string $companyName
     *
     * @return Manifest\Manifest
     */
    public static function createManifestForAccount($accountNumber, $companyName, DateTime $timeStamp = null)
    {
        return Manifest\Manifest::create($accountNumber, $companyName, $timeStamp);
    }

    /**
     * @param string $pckId
     *
     * @return Manifest\Package
     */
    public static function createPackageWithPckId($pckId)
    {
        return Manifest\Package::createWithPckId($pckId);
    }

    /**
     * @param string $itemId
     *
     * @return Manifest\Item
     */
    public static function createItemForPackageWithItemId($itemId)
    {
        return Manifest\Item::createWithItemId($itemId);
    }

    /**
     * @param Manifest\Manifest $manifest
     * @param string $rootElementName
     *
     * @return \SimpleXMLElement
     */
    public static function createXmlFromManifest(Manifest\Manifest $manifest, $rootElementName = 'BwwManifest')
    {
        return Manifest\Xml::fromManifest($manifest, $rootElementName);
    }

    /**
     * @param array $properties
     * @param string $rootElementName
     *
     * @return \SimpleXMLElement
     */
    public static function createXmlFromProperties(array $properties, $rootElementName = 'BwwManifest')
    {
        return Manifest\Xml::fromProperties($properties, $rootElementName);
    }
}
