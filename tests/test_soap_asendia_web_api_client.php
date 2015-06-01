<?php

require_once __DIR__.'/bootstrap.php';

use Cpap\Asendia\Manifest\Xml;
use Cpap\Asendia\WebApiClient\AsendiaWebApiClient;

it('creates shipments', test_create_shipment());
it('adds packages to shipments', test_add_packages_to_shipment());
it('closes shipments', test_close_shipment());
it('retrieves PDF labels', test_retrieve_label_as_pdf());
it('retrieves JPEG labels', test_retrieve_label_as_jpeg());
it('retrieves PNG labels', test_retrieve_label_as_png());

function test_create_shipment()
{
    $asendia_web_api_client = get_asendia_web_api_client(
        'testuser',
        'testpass',
        function (DummyAsendiaWsdlClient $asendia_wsdl_client) {
            return $asendia_wsdl_client
                ->withCreateShipment2Response(get_successful_create_shipment2_result())
            ;
        }
    );

    $created_shipment = $asendia_web_api_client->createShipment();

    return
        'TESTSHIPMENT' === $created_shipment->getShipment() &&
        'open' === $created_shipment->getStatus()
    ;
}

function test_add_packages_to_shipment()
{
    $shipment_number = 'TESTSHIPMENT';
    $manifest = get_manifest_from_facade();
    $label_type = AsendiaWebApiClient::LABEL_TYPE_PDF;

    $asendia_web_api_client = get_asendia_web_api_client(
        'testuser',
        'testpass',
        function (DummyAsendiaWsdlClient $asendia_wsdl_client) use ($shipment_number, $manifest, $label_type) {
            return $asendia_wsdl_client
                ->withAddPackagesToShipment2(
                    get_successful_add_packages_to_shipment2_result(),
                    $shipment_number,
                    Xml::fromManifest($manifest)->asXML(),
                    $label_type
                )
            ;
        }
    );

    $added_shipment_packages = $asendia_web_api_client->addPackagesToShipment(
        $shipment_number,
        $manifest,
        $label_type
    );

    return
        'TESTSHIPMENT' === $added_shipment_packages->getShipment() &&
        2 === count($added_shipment_packages->getPackages()) &&
        'BW00709000019' === $added_shipment_packages->getPackages()[0]->getPckId() &&
        'BWW_13940_BW00709000019.pdf' === $added_shipment_packages->getPackages()[0]->getLabelFile() &&
        'BW00709012345' === $added_shipment_packages->getPackages()[1]->getPckId() &&
        'BWW_13940_BW00709012345.pdf' === $added_shipment_packages->getPackages()[1]->getLabelFile()
    ;
}

function test_close_shipment()
{
    $shipment_number = 'TESTSHIPMENT';

    $asendia_web_api_client = get_asendia_web_api_client(
        'testuser',
        'testpass',
        function (DummyAsendiaWsdlClient $asendia_wsdl_client) use ($shipment_number) {
            return $asendia_wsdl_client
                ->withCloseShipment2(get_successful_close_shipment2(), $shipment_number)
            ;
        }
    );

    $closed_shipment = $asendia_web_api_client->closeShipment($shipment_number);

    return
        'TESTSHIPMENT' === $closed_shipment->getShipment() &&
        'closed' === $closed_shipment->getStatus()
    ;
}

function test_retrieve_label_as_pdf()
{
    $filename = 'BWW_13940_BW00709000019.pdf';

    $asendia_web_api_client = get_asendia_web_api_client(
        'testuser',
        'testpass',
        function (DummyAsendiaWsdlClient $asendia_wsdl_client) use ($filename) {
            return $asendia_wsdl_client
                ->withRetrieveLabelAsPdf(get_successful_retrieve_label_as_pdf(), $filename)
            ;
        }
    );

    $pdf_label = $asendia_web_api_client->retrieveLabelAsPdf($filename);

    return
        $filename === $pdf_label->getLabelFile() &&
        'Test PDF' === $pdf_label->getContent()
    ;
}

function test_retrieve_label_as_jpeg()
{
    $filename = 'BWW_13940_BW00709000019.jpg';

    $asendia_web_api_client = get_asendia_web_api_client(
        'testuser',
        'testpass',
        function (DummyAsendiaWsdlClient $asendia_wsdl_client) use ($filename) {
            return $asendia_wsdl_client
                ->withRetrieveLabelAsJpeg(get_successful_retrieve_label_as_jpeg(), $filename)
            ;
        }
    );

    $jpeg_label = $asendia_web_api_client->retrieveLabelAsJpeg($filename);

    return
        $filename === $jpeg_label->getLabelFile() &&
        'Test JPEG' === $jpeg_label->getContent()
    ;
}

function test_retrieve_label_as_png()
{
    $filename = 'BWW_13940_BW00709000019.png';

    $asendia_web_api_client = get_asendia_web_api_client(
        'testuser',
        'testpass',
        function (DummyAsendiaWsdlClient $asendia_wsdl_client) use ($filename) {
            return $asendia_wsdl_client
                ->withRetrieveLabelAsPng(get_successful_retrieve_label_as_png(), $filename)
            ;
        }
    );

    $png_label = $asendia_web_api_client->retrieveLabelAsPng($filename);

    return
        $filename === $png_label->getLabelFile() &&
        'Test PNG' === $png_label->getContent()
    ;
}

function get_successful_create_shipment2_result()
{
    $result = new stdClass();
    $result->CreateShipment2Result = '<?xml version="1.0" encoding="utf-8" standalone="yes"?><cn22webapi>
  <action>CreateShipment2</action>
  <errcode>0</errcode>
  <errortext>Success: Shipment TESTSHIPMENT Created</errortext>
  <result type="CreateShipment2">
    <shipment status="open">TESTSHIPMENT</shipment>
  </result>
</cn22webapi>';

    return $result;
}

function get_successful_add_packages_to_shipment2_result()
{
    $result = new stdClass();
    $result->AddPackagesToShipment2Result = '<?xml version="1.0" encoding="utf-8" standalone="yes"?><cn22webapi>
  <action>AddPackagesToShipment2</action>
  <errcode>0</errcode>
  <errortext>Success: Shipment TESTSHIPMENT Created</errortext>
  <result type="AddPackagesToShipment2" status="open">
    <shipment>TESTSHIPMENT</shipment>
    <package>
      <PckId>BW00709000019</PckId>
      <LabelFile>BWW_13940_BW00709000019.pdf</LabelFile>
    </package>
    <package>
      <PckId>BW00709012345</PckId>
      <LabelFile>BWW_13940_BW00709012345.pdf</LabelFile>
    </package>
  </result>
</cn22webapi>';

    return $result;
}

function get_successful_close_shipment2()
{
    $result = new stdClass();
    $result->errcode = 0;
    $result->CloseShipment2 = '<?xml version="1.0" encoding="utf-8" standalone="yes"?><cn22webapi>
  <action>CloseShipment2</action>
  <errcode>0</errcode>
  <errortext>Success: Open Shipment TESTSHIPMENT closed.</errortext>
  <result type="CloseShipment2">
    <shipment status="closed">TESTSHIPMENT</shipment>
  </result>
</cn22webapi>';

    return $result;
}

function get_successful_retrieve_label_as_pdf()
{
    $result = new stdClass();
    $result->RetrieveLabelAsPdfResult = 'VGVzdCBQREY=';

    return $result;
}

function get_successful_retrieve_label_as_jpeg()
{
    $result = new stdClass();
    $result->RetrieveLabelAsJpegResult = 'VGVzdCBKUEVH';

    return $result;
}

function get_successful_retrieve_label_as_png()
{
    $result = new stdClass();
    $result->RetrieveLabelAsPngResult = 'VGVzdCBQTkc=';

    return $result;
}
