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

```bash
$> composer require cpap/asendia
```

While in early development, you may be required to be a little more specific:

```bash
$> composer require cpap/asendia:^0.0@dev
```


Basic Usage
-----------

### Creating an Assendia Web API Client

Create an Asendia Web API Client from login and password using the production
WSDL URI.

```php
use Cpap\Asendia\WebApiClient\Adapter\Soap\SoapAsendiaWebApiClient;

$asendia = SoapAsendiaWebApiClient::fromCredentialsAndProductionWsdl(
    $login,
    $password
);
```

Create an Asendia Web API Client from login and password using the testing WSDL
URI.

```php
use Cpap\Asendia\WebApiClient\Adapter\Soap\SoapAsendiaWebApiClient;

$asendia = SoapAsendiaWebApiClient::fromCredentialsAndTestingWsdl(
    $login,
    $password
);
```

Create an Asendia Web API Client from login and password using the WSDL URI
specified.

```php
use Cpap\Asendia\WebApiClient\Adapter\Soap\SoapAsendiaWebApiClient;

$asendia = SoapAsendiaWebApiClient::fromCredentialsAndWsdl(
    $login,
    $password,
    $wsdl
);
```

Example of how one might create an Asendia Web API Client from login and
password using the WSDL URI for the current environment.

```php
use Cpap\Asendia\WebApiClient\Adapter\Soap\SoapAsendiaWebApiClient;

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
echo $createdShipment->getShipment()."\n"; // the number for the newly created shipment.
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
