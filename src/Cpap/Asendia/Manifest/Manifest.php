<?php

namespace Cpap\Asendia\Manifest;

use DateTime;

class Manifest implements Properties
{
    use WithProperties;

    private function __construct() { }

    public static function create($accountNumber, $companyName, DateTime $timeStamp = null)
    {
        return (new static())
            ->withHeader(Header::create($accountNumber, $companyName, $timeStamp))
            ->withProperty('Package', [])
        ;
    }

    public function withTimestamp(DateTime $timeStamp)
    {
        $instance = clone($this);

        $header = $instance->getProperty('Header');

        return $instance->withHeader($header->withTimestamp($timeStamp));
    }

    public function withPackage(Package $package)
    {
        $instance = clone($this);

        $packages = $instance->getProperty('Package');
        $packages[] = $package;

        return $instance->withProperty('Package', $packages);
    }

    public function getPropertyNames()
    {
        return [
            'Header',
            'Package',
        ];
    }
}
