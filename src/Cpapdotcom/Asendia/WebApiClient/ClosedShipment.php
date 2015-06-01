<?php

namespace Cpapdotcom\Asendia\WebApiClient;

class ClosedShipment
{
    /**
     * @var string
     */
    private $shipment;

    /**
     * @var string
     */
    private $status;

    /**
     * @param string $shipment
     * @param string $status
     */
    public function __construct($shipment, $status)
    {
        $this->shipment = $shipment;
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getShipment()
    {
        return $this->shipment;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }
}
