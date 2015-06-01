<?php

namespace Cpap\Asendia\WebApiClient;

use Cpap\Asendia\Manifest\Manifest;

interface AsendiaWebApiClient
{
    const LABEL_TYPE_NONE = 0;
    const LABEL_TYPE_PDF = 1;
    const LABEL_TYPE_PNG = 2;
    const LABEL_TYPE_JPEG = 3;

    /**
     * @return CreatedShipment
     */
    function createShipment();

    /**
     * @param string $shipmentNumber
     * @param array|Manifest $manifest
     * @param int $labelType
     *
     * @return AddedShipmentPackages
     */
    function addPackagesToShipment($shipmentNumber, $manifest, $labelType);

    /**
     * @param string $shipmentNumber
     *
     * @return ClosedShipment
     */
    function closeShipment($shipmentNumber);

    /**
     * @param string $filename
     *
     * @return PdfLabel
     */
    function retrieveLabelAsPdf($filename);

    /**
     * @param string $filename
     *
     * @return JpegLabel
     */
    function retrieveLabelAsJpeg($filename);

    /**
     * @param string $filename
     *
     * @return PngLabel
     */
    function retrieveLabelAsPng($filename);
}
