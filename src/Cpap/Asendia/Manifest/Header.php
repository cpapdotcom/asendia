<?php

namespace Cpap\Asendia\Manifest;

use DateTime;

class Header implements Properties
{
    use WithProperties;

    public static function create($accountNumber, $companyName, DateTime $timeStamp = null)
    {
        $timeStamp = $timeStamp ?: new DateTime();

        return (new static())
            ->withAccountNumber($accountNumber)
            ->withCompanyName($companyName)
            ->withTimeStamp($timeStamp)
        ;
    }

    public function withTimeStamp(DateTime $timeStamp)
    {
        $instance = clone($this);

        return $instance
            ->withFileTimeStamp($timeStamp->format('F j, Y h:i:s A'))
            ->withTimeZone($timeStamp->format('T'))
        ;
    }

    public function getPropertyNames()
    {
        return [
            'CompanyName',
            'AccountNumber',
            'FileTimeStamp',
            'TimeZone',
        ];
    }
}
