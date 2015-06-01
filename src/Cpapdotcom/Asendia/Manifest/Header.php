<?php

namespace Cpapdotcom\Asendia\Manifest;

use DateTime;

/**
 * @method Header withCompanyName(string $companyName)
 * @method Header withAccountNumber(string $accountNumber)
 * @method Header withFileTimeStamp(string $timeStamp)
 * @method Header withTimeZone(string $timeZone)
 */
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
