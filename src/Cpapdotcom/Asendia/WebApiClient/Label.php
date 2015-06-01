<?php

namespace Cpapdotcom\Asendia\WebApiClient;

abstract class Label
{
    /**
     * @var string
     */
    private $labelFile;

    /**
     * @var string
     */
    private $encodedContent;

    /**
     * @param string $labelFile
     * @param string $encodedContent
     */
    public function __construct($labelFile, $encodedContent)
    {
        $this->labelFile = $labelFile;
        $this->encodedContent = $encodedContent;
    }

    /**
     * @return string
     */
    public function getLabelFile()
    {
        return $this->labelFile;
    }

    /**
     * @return string
     */
    public function getEncodedContent()
    {
        return $this->encodedContent;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return base64_decode($this->encodedContent);
    }

    /**
     * @param string $filename
     */
    public function writeContentToFile($filename)
    {
        file_put_contents($filename, base64_decode($this->encodedContent));
    }
}
