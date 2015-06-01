<?php

namespace Cpapdotcom\Asendia\WebApiClient;

class Package
{
    /**
     * @var string
     */
    private $pckId;

    /**
     * @var string
     */
    private $labelFile;

    /**
     * @param string $pckId
     * @param string $labelFile
     */
    public function __construct($pckId, $labelFile)
    {
        $this->pckId = $pckId;
        $this->labelFile = $labelFile;
    }

    /**
     * @return string
     */
    public function getPckId()
    {
        return $this->pckId;
    }

    /**
     * @return string
     */
    public function getLabelFile()
    {
        return $this->labelFile;
    }

}
