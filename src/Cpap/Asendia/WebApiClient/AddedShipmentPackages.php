<?php

namespace Cpap\Asendia\WebApiClient;

class AddedShipmentPackages
{
    /**
     * @var string
     */
    private $shipment;

    /**
     * @var Package[]
     */
    private $packages;

    /**
     * @param string $shipment
     * @param Package[] $packages
     */
    public function __construct($shipment, array $packages = [])
    {
        $this->shipment = $shipment;
        $this->packages = $packages;
    }

    /**
     * @return string
     */
    public function getShipment()
    {
        return $this->shipment;
    }

    /**
     * @return Package[]
     */
    public function getPackages()
    {
        return $this->packages;
    }
}
