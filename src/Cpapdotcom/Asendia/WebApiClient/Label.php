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
    private $content;

    /**
     * @param string $labelFile
     * @param string $content
     */
    public function __construct($labelFile, $content)
    {
        $this->labelFile = $labelFile;
        $this->content = $content;
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
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $filename
     */
    public function writeContentToFile($filename)
    {
        file_put_contents($filename, $this->content);
    }
}
