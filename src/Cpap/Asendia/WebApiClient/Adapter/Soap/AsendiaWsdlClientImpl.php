<?php

namespace Cpap\Asendia\WebApiClient\Adapter\Soap;

use SoapClient;

class AsendiaWsdlClientImpl implements AsendiaWsdlClient
{
    /**
     * @var SoapClient
     */
    private $soapClient;

    /**
     * @param SoapClient $soapClient
     */
    public function __construct(SoapClient $soapClient)
    {
        $this->soapClient = $soapClient;
    }

    public function CreateShipment2(array $arguments)
    {
        return $this->soapClient->CreateShipment2($arguments);
    }

    public function AddPackagesToShipment2(array $arguments)
    {
        return $this->soapClient->AddPackagesToShipment2($arguments);
    }

    public function CloseShipment2(array $arguments)
    {
        return $this->soapClient->CloseShipment2($arguments);
    }

    public function RetrieveLabelAsPdf(array $arguments)
    {
        return $this->soapClient->RetrieveLabelAsPdf($arguments);
    }

    public function RetrieveLabelAsJpeg(array $arguments)
    {
        return $this->soapClient->RetrieveLabelAsJpeg($arguments);
    }

    public function RetrieveLabelAsPng(array $arguments)
    {
        return $this->soapClient->RetrieveLabelAsPng($arguments);
    }
}
