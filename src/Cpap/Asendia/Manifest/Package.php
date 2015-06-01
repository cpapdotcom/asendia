<?php

namespace Cpap\Asendia\Manifest;

class Package implements Properties
{
    use WithProperties;

    private function __construct() { }

    public static function createWithPckId($pckId)
    {
        return (new static())
            ->withPckId($pckId)
            ->withProperty('Item', [])
        ;
    }

    public function withItem(Item $item)
    {
        $instance = clone($this);

        $items = $instance->getProperty('Item', []);
        $items[] = $item->withPckId($this->getProperty('PckId'));

        return $instance->withProperty('Item', $items);
    }

    public function withAddressLines(array $addressLines)
    {
        $instance = clone($this);

        for ($i = 0; $i < 3; $i++) {
            $propertyName = sprintf("AddressLine%d", $i+1);
            $propertyValue =  isset($addressLines[$i]) ? $addressLines[$i] : null;
            $instance = $instance->withProperty($propertyName, $propertyValue);
        }

        return $instance;
    }

    public function getPropertyNames()
    {
        return [
            'PckId',
            'OrderId',
            'LastName',
            'FirstName',
            'MiddleInitial',
            'AddressLine1',
            'AddressLine2',
            'AddressLine3',
            'City',
            'Province',
            'PostalCode',
            'CountryCode',
            'Phone',
            'Email',
            'PckWeight',
            'PckType',
            'ServiceType',
            'PckDescription',
            'ShippingCost',
            'DutyTaxHandling',
            'CustomsBarCode',
            'Item',
        ];
    }

    protected function getNumericPropertyNames()
    {
        return [
            'ShippingCost',
            'DutyTaxHandling',
        ];
    }
}
