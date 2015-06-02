<?php

namespace Cpapdotcom\Asendia\WebApiClient;

use Cpapdotcom\Asendia\Manifest\Manifest;

interface AsendiaWebApiClient
{
    const LABEL_TYPE_NONE = 0;
    const LABEL_TYPE_PDF = 1;
    const LABEL_TYPE_PNG = 2;
    const LABEL_TYPE_JPEG = 3;

    /**
     * @return CreatedShipment
     */
    public function createShipment();

    /**
     * @param string $shipmentnumber
     * @param array|Manifest $manifest
     * @param int $labelType
     *
     * @return AddedShipmentPackages
     */
    public function addPackagesToShipment($shipmentnumber, $manifest, $labelType);

    /**
     * @param string $shipmentNumber
     *
     * @return ClosedShipment
     */
    public function closeShipment($shipmentNumber);

    /**
     * @param int $labelType
     * @param string $filename
     *
     * @return Label
     */
    public function retrieveLabel($labelType, $filename);

    /**
     * @param string $filename
     *
     * @return PdfLabel
     */
    public function retrieveLabelAsPdf($filename);

    /**
     * @param string $filename
     *
     * @return JpegLabel
     */
    public function retrieveLabelAsJpeg($filename);

    /**
     * @param string $filename
     *
     * @return PngLabel
     */
    public function retrieveLabelAsPng($filename);
}
