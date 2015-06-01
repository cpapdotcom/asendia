<?php

use Cpap\Asendia\Manifest;

/**
 * @return Manifest\Manifest
 */
function get_manifest_from_facade()
{
    $accountNumber = '123456789012345';
    $companyName = 'Your Company Name';
    $timeStamp = new DateTime('2011-03-01 01:12:00PM EST');

    return Manifest::createManifestForAccount($accountNumber, $companyName, $timeStamp)
        ->withPackage(Manifest::createPackageWithPckId('BW00709000019')
            ->withOrderId('89105221002001100217')
            ->withLastName('Doe')
            ->withFirstName('Jane')
            ->withMiddleInitial('S')
            ->withAddressLines([
                '17 Robilliard Way',
            ])
            ->withCity('Sebastopol')
            ->withProvince('Bonshaw')
            ->withPostalCode('3356')
            ->withCountryCode('AU')
            //->withPhone()
            //->withEmail()
            ->withPckWeight('3.58')
            ->withPckType('M')
            ->withServiceType('PAR')
            ->withPckDescription('Clothing')
            ->withShippingCost('20.21')
            ->withDutyTaxHandling('10.83')
            ->withCustomsBarCode('LM473124829US')
            ->withItem(Manifest::createItemForPackageWithItemId('2929840')
                ->withItemDescription('Shirt')
                ->withCustomsDescription('Shirt')
                ->withQuantity(1)
                ->withUnitPrice('10.00')
                ->withCountryOfOrigin('US')
                ->withHTSNumber('123456789')
            )
            ->withItem(Manifest::createItemForPackageWithItemId('2929841')
                ->withItemDescription('Pants')
                ->withCustomsDescription('Pants')
                ->withQuantity(2)
                ->withUnitPrice('15.00')
                ->withCountryOfOrigin('US')
                ->withHTSNumber('987654321')
            )
        )
        ->withPackage(Manifest::createPackageWithPckId('BW00709012345')
            ->withOrderId('89105221002001100217')
            ->withLastName('Smith')
            ->withFirstName('John')
            ->withMiddleInitial('Q')
            ->withAddressLines([
                '28A CLIFTON ST',
                'Apartment 203',
            ])
            ->withCity('CAMPBELLTOWN')
            ->withProvince('SYDNEY')
            ->withPostalCode('2560')
            ->withCountryCode('AU')
            ->withPhone('jsmith@gmail.com')
            //->withEmail()
            ->withPckWeight('1.25')
            ->withPckType('S')
            ->withServiceType('PAR')
            ->withPckDescription('Clothing')
            //->withShippingCost()
            //->withDutyTaxHandling()
            ->withCustomsBarCode('LM473124829US')
            ->withItem(Manifest::createItemForPackageWithItemId('123456789')
                ->withItemDescription('Pants')
                ->withCustomsDescription('100% cotton')
                ->withQuantity(1)
                ->withUnitPrice('25.00')
                ->withCountryOfOrigin('US')
                //->withHTSNumber()
            )
        )
    ;
}

function get_manifest_from_properties()
{
    return [
        'Header' => [
            'CompanyName' => 'Your Company Name',
            'AccountNumber' => '123456789012345',
            'FileTimeStamp' => 'March 1, 2011 01:12:00 PM',
            'TimeZone' => 'EST',
        ],
        'Package' => [
            [
                'PckId' => 'BW00709000019',
                'OrderId' => '89105221002001100217',
                'LastName' => 'Doe',
                'FirstName' => 'Jane',
                'MiddleInitial' => 'S',
                'AddressLine1' => '17 Robilliard Way',
                'AddressLine2' => null,
                'AddressLine3' => null,
                'City' => 'Sebastopol',
                'Province' => 'Bonshaw',
                'PostalCode' => '3356',
                'CountryCode' => 'AU',
                'Phone' => null,
                'Email' => null,
                'PckWeight' => '3.58',
                'PckType' => 'M',
                'ServiceType' => 'PAR',
                'PckDescription' => 'Clothing',
                'ShippingCost' => '20.21',
                'DutyTaxHandling' => '10.83',
                'CustomsBarCode' => 'LM473124829US',
                'Item' => [
                    [
                        'PckId' => 'BW00709000019',
                        'ItemId' => '2929840',
                        'ItemDescription' => 'Shirt',
                        'CustomsDescription' => 'Shirt',
                        'Quantity' => 1,
                        'UnitPrice' => '10.00',
                        'CountryOfOrigin' => 'US',
                        'HTSNumber' => '123456789',
                    ],
                    [
                        'PckId' => 'BW00709000019',
                        'ItemId' => '2929841',
                        'ItemDescription' => 'Pants',
                        'CustomsDescription' => 'Pants',
                        'Quantity' => 2,
                        'UnitPrice' => '15.00',
                        'CountryOfOrigin' => 'US',
                        'HTSNumber' => '987654321',
                    ],
                ],
            ],
            [
                'PckId' => 'BW00709012345',
                'OrderId' => '89105221002001100217',
                'LastName' => 'Smith',
                'FirstName' => 'John',
                'MiddleInitial' => 'Q',
                'AddressLine1' => '28A CLIFTON ST',
                'AddressLine2' => 'Apartment 203',
                'AddressLine3' => null,
                'City' => 'CAMPBELLTOWN',
                'Province' => 'SYDNEY',
                'PostalCode' => '2560',
                'CountryCode' => 'AU',
                'Phone' => 'jsmith@gmail.com',
                'Email' => null,
                'PckWeight' => '1.25',
                'PckType' => 'S',
                'ServiceType' => 'PAR',
                'PckDescription' => 'Clothing',
                'CustomsBarCode' => 'LM473124829US',
                'Item' => [
                    [
                        'PckId' => 'BW00709012345',
                        'ItemId' => '123456789',
                        'ItemDescription' => 'Pants',
                        'CustomsDescription' => '100% cotton',
                        'Quantity' => '1',
                        'UnitPrice' => '25.00',
                        'CountryOfOrigin' => 'US',
                        'HTSNumber' => null,
                    ],
                ],
            ]
        ],
    ];
}
