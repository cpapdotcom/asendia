<?php

namespace Cpapdotcom\Asendia\WebApiClient\Adapter\Soap;

interface AsendiaWsdlClient
{
    public function CreateShipment2(array $arguments);
    public function AddPackagesToShipment2(array $arguments);
    public function CloseShipment2(array $arguments);
    public function RetrieveLabelAsPdf(array $argumenst);
    public function RetrieveLabelAsJpeg(array $arguments);
    public function RetrieveLabelAsPng(array $arguments);
}
