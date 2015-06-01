<?php

namespace Cpap\Asendia\WebApiClient\Adapter\Soap;

use Cpap\Asendia\Manifest\Manifest;
use Cpap\Asendia\Manifest\Xml;
use Cpap\Asendia\WebApiClient\AddedShipmentPackages;
use Cpap\Asendia\WebApiClient\AsendiaWebApiClient;
use Cpap\Asendia\WebApiClient\ClosedShipment;
use Cpap\Asendia\WebApiClient\CreatedShipment;
use Cpap\Asendia\WebApiClient\Error;
use Cpap\Asendia\WebApiClient\JpegLabel;
use Cpap\Asendia\WebApiClient\LabelNotFound;
use Cpap\Asendia\WebApiClient\LoginFailure;
use Cpap\Asendia\WebApiClient\Package;
use Cpap\Asendia\WebApiClient\PdfLabel;
use Cpap\Asendia\WebApiClient\PngLabel;
use InvalidArgumentException;
use SimpleXMLElement;
use SoapClient;

class SoapAsendiaWebApiClient implements AsendiaWebApiClient
{
    /**
     * Production WSDL URI.
     */
    const PRODUCTION_WSDL = 'http://client.asendiausa.com/CN22WebApi/DataFeed.svc?wsdl';

    /**
     * Testing WSDL URI.
     */
    const TESTING_WSDL = 'http://client.asendiausa.com/CN22WebApiCustomerTest/DataFeed.svc?wsdl';

    /**
     * @var AsendiaWsdlClient
     */
    private $asendiaWsdlClient;

    /**
     * @var string
     */
    private $login;

    /**
     * @var string
     */
    private $password;

    /**
     * @param AsendiaWsdlClient $asendiaWsdlClient
     * @param string $login
     * @param string $password
     */
    public function __construct(AsendiaWsdlClient $asendiaWsdlClient, $login, $password)
    {
        $this->asendiaWsdlClient = $asendiaWsdlClient;
        $this->login = $login;
        $this->password = $password;
    }

    /**
     * Create SOAP Asendia Web API Client from credentials using a specified WSDL URI.
     *
     * @param string $login
     * @param string $password
     * @param string $wsdl
     *
     * @return static
     */
    public static function fromCredentialsAndWsdl($login, $password, $wsdl)
    {
        $soapClient = new AsendiaWsdlClientImpl(new SoapClient($wsdl));

        return new static($soapClient, $login, $password);
    }

    /**
     * Create a SOAP Asendia Web API Client from credentials using the production WSDL URI.
     *
     * @param string $login
     * @param string $password
     *
     * @return SoapAsendiaWebApiClient
     */
    public static function fromCredentialsAndProductionWsdl($login, $password)
    {
        return static::fromCredentialsAndWsdl(
            $login,
            $password,
            static::PRODUCTION_WSDL
        );
    }

    /**
     * Create a SOAP Asendia Web API Client from credentials using the testing WSDL URI.
     *
     * @param string $login
     * @param string $password
     *
     * @return SoapAsendiaWebApiClient
     */
    public static function fromCredentialsAndTestingWsdl($login, $password)
    {
        return static::fromCredentialsAndWsdl(
            $login,
            $password,
            static::TESTING_WSDL
        );
    }

    /**
     * {@inheritdoc}
     */
    public function createShipment()
    {
        $response = $this->asendiaWsdlClient->CreateShipment2([
            'login' => $this->login,
            'password' => $this->password,
        ]);

        $responseXmlElement = new SimpleXMLElement($response->CreateShipment2Result);

        $this->processPotentialErrorResponses($responseXmlElement);

        /** @var SimpleXMLElement $shipment */
        $shipmentElement = $responseXmlElement->xpath('/cn22webapi/result/shipment')[0];

        $shipment = trim((string) $shipmentElement);
        $status = (string) $shipmentElement['status'];

        return new CreatedShipment($shipment, $status);
    }

    /**
     * {@inheritdoc}
     */
    public function addPackagesToShipment($shipmentNumber, $manifest, $labelType)
    {
        $xml = self::resolveManifestToXml($manifest);

        $response = $this->asendiaWsdlClient->AddPackagesToShipment2([
            'login' => $this->login,
            'password' => $this->password,
            'shipmentNumber' => $shipmentNumber,
            'xmlstring' => $xml,
            'labelType' => $labelType,
        ]);

        $responseXmlElement = new SimpleXMLElement($response->AddPackagesToShipment2Result);

        $this->processPotentialErrorResponses($responseXmlElement);

        /** @var SimpleXMLElement $shipment */
        $shipmentElement = $responseXmlElement->xpath('/cn22webapi/result/shipment')[0];

        $shipment = trim((string) $shipmentElement);
        $packages = [];

        foreach ($responseXmlElement->xpath('/cn22webapi/result/package') as $packageElement) {
            $pckId = trim((string) ($packageElement->xpath('PckId')[0]));
            $labelFile = trim((string) ($packageElement->xpath('LabelFile')[0]));

            $packages[] = new Package($pckId, $labelFile);
        }

        return new AddedShipmentPackages($shipment, $packages);
    }

    /**
     * {@inheritdoc}
     */
    public function closeShipment($shipmentNumber)
    {
        $response = $this->asendiaWsdlClient->CloseShipment2([
            'login' => $this->login,
            'password' => $this->password,
            'shipmentNumber' => $shipmentNumber,
        ]);

        $responseXmlElement = new SimpleXMLElement($response->CloseShipment2);

        $this->processPotentialErrorResponses($responseXmlElement);

        /** @var SimpleXMLElement $shipment */
        $shipmentElement = $responseXmlElement->xpath('/cn22webapi/result/shipment')[0];

        $shipment = trim((string) $shipmentElement);
        $status = (string) $shipmentElement['status'];

        return new ClosedShipment($shipment, $status);
    }

    /**
     * {@inheritdoc}
     */
    public function retrieveLabelAsPdf($filename)
    {
        $response = $this->asendiaWsdlClient->RetrieveLabelAsPdf([
            'login' => $this->login,
            'password' => $this->password,
            'filename' => $filename,
        ]);

        if (! $response->RetrieveLabelAsPdfResult) {
            throw new LabelNotFound(sprintf('Label for filename "%s" was not found.', $filename));
        }

        $labelFile = $filename;
        $encodedContent = $response->RetrieveLabelAsPdfResult;

        return new PdfLabel($labelFile, $encodedContent);
    }

    /**
     * {@inheritdoc}
     */
    public function retrieveLabelAsJpeg($filename)
    {
        $response = $this->asendiaWsdlClient->RetrieveLabelAsJpeg([
            'login' => $this->login,
            'password' => $this->password,
            'filename' => $filename,
        ]);

        if (! $response->RetrieveLabelAsJpegResult) {
            throw new LabelNotFound(sprintf('Label for filename "%s" was not found.', $filename));
        }

        $labelFile = $filename;
        $encodedContent = $response->RetrieveLabelAsJpegResult;

        return new JpegLabel($labelFile, $encodedContent);
    }

    /**
     * {@inheritdoc}
     */
    public function retrieveLabelAsPng($filename)
    {
        $response = $this->asendiaWsdlClient->RetrieveLabelAsPng([
            'login' => $this->login,
            'password' => $this->password,
            'filename' => $filename,
        ]);

        if (! $response->RetrieveLabelAsPngResult) {
            throw new LabelNotFound(sprintf('Label for filename "%s" was not found.', $filename));
        }

        $labelFile = $filename;
        $encodedContent = $response->RetrieveLabelAsPngResult;

        return new PngLabel($labelFile, $encodedContent);
    }

    private static function resolveManifestToXml($manifest)
    {
        if (is_array($manifest)) {
            $xmlstring = Xml::fromProperties($manifest)->asXML();
        } elseif ($manifest instanceof Manifest) {
            $xmlstring = Xml::fromManifest($manifest)->asXML();
        } elseif (is_string($manifest)) {
            $xmlstring = $manifest;
        } else {
            $message = sprintf(
                'Manifest must be an array of properties, and instance of %s, or an XML string.',
                'Cpap\Asendia\Manifest\Manifest'
            );

            throw new InvalidArgumentException($message);
        }

        return $xmlstring;
    }

    private function processPotentialErrorResponses(SimpleXMLElement $element)
    {
        $errcode = (int) $element->xpath('/cn22webapi/errcode')[0];

        if ($errcode === 0) {
            return;
        }

        $errortext = (string) $element->xpath('/cn22webapi/errortext')[0];
        $action = (string) $element->xpath('/cn22webapi/action')[0];

        throw new Error(sprintf('%s: %s', $action, $errortext), $errcode);
    }
}
