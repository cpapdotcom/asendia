<?php

require_once 'vendor/autoload.php';
require_once 'tests/fixtures.php';

use Cpapdotcom\Asendia\WebApiClient\Adapter\Soap\AsendiaWsdlClientImpl;
use Cpapdotcom\Asendia\WebApiClient\Adapter\Soap\SoapAsendiaWebApiClient;
use Cpapdotcom\Asendia\WebApiClient\AsendiaWebApiClient;
use Cpapdotcom\Asendia\WebApiClient\Error;

if (3 > count($argv)) {
    printf("Example application for exercising the Asendia Web API Client.\n");
    printf("\n");
    printf("Will connect to the testing WSDL:\n");
    printf(" * %s\n", SoapAsendiaWebApiClient::TESTING_WSDL);
    printf("\n");
    printf("Usage: %s <login> <password> [<labelType>]\n", $argv[0]);
    printf("\n");
    die;
}

if (3 === count($argv)) {
    list ($noop, $login, $password) = $argv;
    $labelType = AsendiaWebApiClient::LABEL_TYPE_PDF;
} else {
    list ($noop, $login, $password, $labelType) = $argv;
    switch (strtolower($labelType)) {
        case 'none':
        case '0':
            $labelType = AsendiaWebApiClient::LABEL_TYPE_NONE;
            break;

        case 'pdf':
        case '1':
            $labelType = AsendiaWebApiClient::LABEL_TYPE_PDF;
            break;

        case 'png':
        case '2':
            $labelType = AsendiaWebApiClient::LABEL_TYPE_PNG;
            break;

        case 'jpeg':
        case 'jpg':
        case '3':
            $labelType = AsendiaWebApiClient::LABEL_TYPE_JPEG;
            break;
    }
}

// This is the complex way to get a testing Asendia client.
// This method allows us to capture the original SoapClient
// instance for debugging. (we will check the last request
// made by SoapClient any time we see an exception!)
$soapClient = new SoapClient(SoapAsendiaWebApiClient::TESTING_WSDL, ['trace' => 1]);
$wsdlClient = new AsendiaWsdlClientImpl($soapClient);
$asendia = new SoapAsendiaWebApiClient($wsdlClient, $login, $password);



try {
    printf("Creating a shipment.\n");

    $createdShipment = $asendia->createShipment();

    printf(" * shipment number: %s\n", $createdShipment->getShipment());
    printf(" * status:          %s\n", $createdShipment->getStatus());
    printf("\n");
} catch (Error $e) {
    printf(" * ERROR: %s\n\n".$e->getMessage());
    if ($soapClient) {
        print_r($soapClient->__getLastRequestHeaders());
        print_r($soapClient->__getLastRequest());
    }
    throw $e;
}



try {
    printf("Adding packages to a shipment.\n");

    $manifest = get_manifest_from_facade($randomize = true);

    $addedShipmentPackages = $asendia->addPackagesToShipment(
        $createdShipment->getShipment(),
        $manifest,
        $labelType
    );

    printf(" * shipment number: %s\n", $addedShipmentPackages->getShipment());
    foreach ($addedShipmentPackages->getPackages() as $package) {
        printf(" * Added package\n");
        printf("   * PckId:     %s\n", $package->getPckId());
        printf("   * LabelFile: %s\n", $package->getLabelFile());
        printf("\n");
    }
} catch (Error $e) {
    printf(" * ERROR: %s\n\n", $e->getMessage());
    if ($soapClient) {
        print_r($soapClient->__getLastRequestHeaders());
        print_r($soapClient->__getLastRequest());
    }
    throw $e;
}



printf("Retrieving labels.\n");

foreach ($addedShipmentPackages->getPackages() as $package) {
    try {
        printf(" * Retrieving PDF label for %s (%s)\n", $package->getPckId(), $package->getLabelFile());

        $label = $asendia->retrieveLabel($labelType, $package->getLabelFile());

        printf("   * Retrieved label file: %s\n", $label->getLabelFile());
        printf("   * Retrieved label size: %d bytes\n", strlen($label->getContent()));

        $temporary = get_temp_file_for_label($label);
        $label->writeContentToFile($temporary);
        printf("   * Retrieved label file: %s\n", $temporary);
        printf("\n");
    } catch (Error $e) {
        printf(" * ERROR: %s\n\n", $e->getMessage());
        if ($soapClient) {
            print_r($soapClient->__getLastRequestHeaders());
            print_r($soapClient->__getLastRequest());
        }
        throw $e;
    }
}



try {
    printf("Closing a shipment.\n");

    $closedShipment = $asendia->closeShipment($createdShipment->getShipment());

    printf(" * shipment number: %s\n", $closedShipment->getShipment());
    printf(" * status:          %s\n", $closedShipment->getStatus());
    printf("\n");
} catch (Error $e) {
    printf(" * ERROR: %s\n\n", $e->getMessage());
    if ($soapClient) {
        print_r($soapClient->__getLastRequestHeaders());
        print_r($soapClient->__getLastRequest());
    }
    throw $e;
}



function get_temp_dir_for_labels()
{
    return __DIR__.'/temp';
}

function ensure_temp_dir_for_labels_exists()
{
    $temp_dir = get_temp_dir_for_labels();

    if (! file_exists($temp_dir)) {
        mkdir($temp_dir);
    }
}

function get_temp_file_for_label(\Cpapdotcom\Asendia\WebApiClient\Label $label)
{
    ensure_temp_dir_for_labels_exists();

    return get_temp_dir_for_labels().'/'.$label->getLabelFile();
}
