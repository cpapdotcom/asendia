<?php

use Cpap\Asendia\WebApiClient\Adapter\Soap\AsendiaWsdlClient;

class DummyAsendiaWsdlClient implements AsendiaWsdlClient
{
    private $login;
    private $password;
    private $createShipment2Response;
    private $addPackagesToShipment2Response;
    private $addPackagesToShipment2Xmlstring;
    private $addPackagesToShipment2ShipmentNumber;
    private $addPackagesToShipment2LabelType;
    private $closeShipment2Response;
    private $closeShipment2ShipmentNumber;
    private $retrieveLabelAsPdfResponse;
    private $retrieveLabelAsPdfFilename;
    private $retrieveLabelAsJpegResponse;
    private $retrieveLabelAsJpegFilename;
    private $retrieveLabelAsPngResponse;
    private $retrieveLabelAsPngFilename;

    public function __construct($login, $password)
    {
        $this->login = $login;
        $this->password = $password;
    }

    public function withCreateShipment2Response(stdClass $response)
    {
        $instance = clone($this);
        $instance->createShipment2Response = $response;

        return $instance;
    }

    public function withAddPackagesToShipment2(stdClass $response, $shipmentNumber, $xmlstring, $labelType)
    {
        $instance = clone($this);
        $instance->addPackagesToShipment2Response = $response;
        $instance->addPackagesToShipment2ShipmentNumber = $shipmentNumber;
        $instance->addPackagesToShipment2Xmlstring = $xmlstring;
        $instance->addPackagesToShipment2LabelType = $labelType;

        return $instance;
    }

    public function withCloseShipment2(stdClass $response, $shipmentNumber)
    {
        $instance = clone($this);
        $instance->closeShipment2Response = $response;
        $instance->closeShipment2ShipmentNumber = $shipmentNumber;

        return $instance;
    }

    public function withRetrieveLabelAsPdf(stdClass $response, $filename)
    {
        $instance = clone($this);
        $instance->retrieveLabelAsPdfResponse = $response;
        $instance->retrieveLabelAsPdfFilename = $filename;

        return $instance;
    }

    public function withRetrieveLabelAsJpeg(stdClass $response, $filename)
    {
        $instance = clone($this);
        $instance->retrieveLabelAsJpegResponse = $response;
        $instance->retrieveLabelAsJpegFilename = $filename;

        return $instance;
    }

    public function withRetrieveLabelAsPng(stdClass $response, $filename)
    {
        $instance = clone($this);
        $instance->retrieveLabelAsPngResponse = $response;
        $instance->retrieveLabelAsPngFilename = $filename;

        return $instance;
    }

    public function CreateShipment2(array $arguments)
    {
        $this->guardCredentials($arguments);

        return $this->createShipment2Response;
    }

    public function AddPackagesToShipment2(array $arguments)
    {
        $this->guardCredentials($arguments);

        $this->guardArgument(
            $this->addPackagesToShipment2Xmlstring,
            $arguments,
            'xmlstring'
        );

        $this->guardArgument(
            $this->addPackagesToShipment2ShipmentNumber,
            $arguments,
            'shipmentNumber'
        );

        $this->guardArgument(
            $this->addPackagesToShipment2LabelType,
            $arguments,
            'labelType'
        );

        return $this->addPackagesToShipment2Response;
    }

    public function CloseShipment2(array $arguments)
    {
        $this->guardCredentials($arguments);

        $this->guardArgument(
            $this->closeShipment2ShipmentNumber,
            $arguments,
            'shipmentNumber'
        );

        return $this->closeShipment2Response;
    }

    public function RetrieveLabelAsPdf(array $arguments)
    {
        $this->guardCredentials($arguments);
        $this->guardArgument($this->retrieveLabelAsPdfFilename, $arguments, 'filename');

        return $this->retrieveLabelAsPdfResponse;
    }

    public function RetrieveLabelAsJpeg(array $arguments)
    {
        $this->guardCredentials($arguments);
        $this->guardArgument($this->retrieveLabelAsJpegFilename, $arguments, 'filename');

        return $this->retrieveLabelAsJpegResponse;
    }

    public function RetrieveLabelAsPng(array $arguments)
    {
        $this->guardCredentials($arguments);
        $this->guardArgument($this->retrieveLabelAsPngFilename, $arguments, 'filename');

        return $this->retrieveLabelAsPngResponse;
    }

    private function guardCredentials(array $arguments)
    {
        $this->guardArgument($this->login, $arguments, 'login');
        $this->guardArgument($this->password, $arguments, 'password');
    }

    private function guardArgument($expected, $arguments, $argument)
    {
        if ($arguments[$argument] !== $expected) {
            throw new RuntimeException(sprintf(
                'Passed %s "%s" does not match expected value of "%s".',
                $argument,
                $arguments[$argument],
                $expected
            ));
        }
    }

    private function guardArgumentTersely($expected, $arguments, $argument)
    {
        if ($arguments[$argument] !== $expected) {
            throw new RuntimeException(sprintf(
                'Passed %s does not match expected value.',
                $argument
            ));
        }
    }
}
