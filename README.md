Asendia Integration
===================

Provides integration with Asendia.

[![Build Status](https://travis-ci.org/cpapdotcom/asendia.svg?branch=master)](https://travis-ci.org/cpapdotcom/asendia)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/cpapdotcom/asendia/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/cpapdotcom/asendia/?branch=master)
[![Code Climate](https://codeclimate.com/github/cpapdotcom/asendia/badges/gpa.svg)](https://codeclimate.com/github/cpapdotcom/asendia)


Requirements
------------

 * PHP 5.4+


Installation
------------

### Using Composer

```bash
$> composer require cpapdotcom/asendia
```

While in early development, you may be required to be a little more specific:

```bash
$> composer require cpapdotcom/asendia:^0.0@dev
```

### PSR-4 or PSR-0 Autoloading

Configure [PSR-4](http://www.php-fig.org/psr/psr-4) to look in
`src/Cpapdotcom/Asendia` with a namespace prefix of
`Cpapdotcom\Asendia\`. For PSR-4, the trailing `\` is important.

Configure [PSR-0](http://www.php-fig.org/psr/psr-0) to look in `src/` for
classes. Depending on PSR-0 implementation, the namespace prefix may be
set to `Cpapdotcom\Asendia`.

### Standalone

This package ships with a standalone [PSR-4](http://www.php-fig.org/psr/psr-4)
autoloader courtesy of [Aura](http://auraphp.com/). Require the `autoload.php`
from the root of this package to make all of the classes in this package
available to an application.

```php
require_once '/path/to/cpapdotcom-asendia/autoload.php';
```


Basic Manifest Usage
--------------------

The Manifest is a programmatic representation of Asendia's Global eFile XML
Data Import Specifications. The end result is to create a Simple XML Element
instance that can be consumed by the Asendia Web API Client.

The `Cpapdotcom\Asendia\Manifest` class is a facade to the Manifest primitive
types and tools to transform both `Manifest\Manifest` instances and property
collections into Simple XML Elements. Its job is to simplify these tasks:

### Creating a Manifest for an account

Creates a `Manifest\Manifest` with the current date and time for the timestamp.

```php
use Cpapdotcom\Asendia\Manifest;

$manifest = Manifest::createManifestForAccount(
    $accountNumber,
    $companyName
);
```

Creates a `Manifest\Manifest` with the specific date and time for the timestamp.

```php
use Cpapdotcom\Asendia\Manifest;

$manifest = Manifest::createManifestForAccount(
    $accountNumber,
    $companyName,
    new DateTime('yesterday')
);
```

### Creating a Package

Creates a `Manifest\Package`.

```php
use Cpapdotcom\Asendia\Manifest;

$package = Manifest::createPackageWithPckId($pckId);
```

### Creating an Item

Creates a `Manifest\Item`.

```php
use Cpapdotcom\Asendia\Manifest;

$package = Manifest::createItemForPackageWithItemId($itemId);
```

### Creating a Simple XML Element from a Manifest

```php
use Cpapdotcom\Asendia\Manifest;

$element = Manifest::createXmlFromManifest($manifest);
```

### Creating a Simple XML Element from a collection of properties

```php
use Cpapdotcom\Asendia\Manifest;

$element = Manifest::createXmlFromProperties($properties);
```


### Example

```php
use Cpapdotcom\Asendia\Manifest;

$manifest = Manifest::createManifestForAccount(
    '123456789012345',
    'Your Company Name'
)
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

//
$manifestAsXml = Manifest::createXmlFromManifest($manifest);
```


Basic Asendia Web API Client Usage
----------------------------------

### Creating an Assendia Web API Client

Create an Asendia Web API Client from login and password using the production
WSDL URI.

```php
use Cpapdotcom\Asendia\WebApiClient\Adapter\Soap\SoapAsendiaWebApiClient;

$asendia = SoapAsendiaWebApiClient::fromCredentialsAndProductionWsdl(
    $login,
    $password
);
```

Create an Asendia Web API Client from login and password using the testing WSDL
URI.

```php
use Cpapdotcom\Asendia\WebApiClient\Adapter\Soap\SoapAsendiaWebApiClient;

$asendia = SoapAsendiaWebApiClient::fromCredentialsAndTestingWsdl(
    $login,
    $password
);
```

Create an Asendia Web API Client from login and password using the WSDL URI
specified.

```php
use Cpapdotcom\Asendia\WebApiClient\Adapter\Soap\SoapAsendiaWebApiClient;

$asendia = SoapAsendiaWebApiClient::fromCredentialsAndWsdl(
    $login,
    $password,
    $wsdl
);
```

Example of how one might create an Asendia Web API Client from login and
password using the WSDL URI for the current environment.

```php
use Cpapdotcom\Asendia\WebApiClient\Adapter\Soap\SoapAsendiaWebApiClient;

$asendia = SoapAsendiaWebApiClient::fromCredentialsAndWsdl(
    $login,
    $password,
    $env === 'production'
        ? SoapAsendiaWebApiClient::PRODUCTION_WSDL
        : SoapAsendiaWebApiClient::TESTING_WSDL
);
```

### Create a shipment

```php
$createdShipment = $asendia->createShipment();

echo $createdShipment->getStatus()."\n"; // should be 'open'
echo $createdShipment->getShipment()."\n"; // the number for the newly created shipment
```

### Add packages to shipments

```php
$addedShipmentPackages = $asendia->addPackagesToShipment(
    $shipmentNumber,
    $manifest,
    AsendiaWebApiClient::LABEL_TYPE_PDF
);

echo $addedShipmentPackages->getShipment()."\n"; // the number for the shipment
foreach ($addedShipmentPackages->getPackages() as $package) {
    echo $package->getPckId()."\n"; // the PckId
    echo $package->getLabelFile()."\n"; // get the filename for the label
}
```

### Close a shipment

```php
$closedShipment = $asendia->closeShipment($shipmentNumber);

echo $closedShipment->getShipment()."\n"; // the number of the shipment
echo $closedShipment->getStatus()."\n"; // should be 'closed'
```

### Retrieve a PDF label

```php
$pdfLabel = $asendia->retrieveLabelAsPdf($filename);

echo $pdfLabel->getLabelFile()."\n"; // the filename of the label
echo $pdfLabel->getEncodedContent()."\n"; // the base64 encoded content
echo $pdfLabel->getContent()."\n"; // the base64 decoded content (binary/raw)
$pdfLabel->writeContentToFile('/path/to/whatever.pdf'); // writes content to file
```

### Retrieve a JPEG label

```php
$jpegLabel = $asendia->retrieveLabelAsJpeg($filename);

echo $jpegLabel->getLabelFile()."\n"; // the filename of the label
echo $jpegLabel->getEncodedContent()."\n"; // the base64 encoded content
echo $jpegLabel->getContent()."\n"; // the base64 decoded content (binary/raw)
$jpegLabel->writeContentToFile('/path/to/whatever.jpg'); // writes content to file
```

### Retrieve a PNG label

```php
$pngLabel = $asendia->retrieveLabelAsPng($filename);

echo $pngLabel->getLabelFile()."\n"; // the filename of the label
echo $pngLabel->getEncodedContent()."\n"; // the base64 encoded content
echo $pngLabel->getContent()."\n"; // the base64 decoded content (binary/raw)
$pngLabel->writeContentToFile('/path/to/whatever.png'); // writes content to file
```


License
-------

MIT, see LICENSE.
