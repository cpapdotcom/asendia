<?php

namespace Cpap\Asendia\Manifest;

class Item implements Properties
{
    use WithProperties;

    private function __construct() { }

    /**
     * Create an Item with an ItemId for a PckId.
     *
     * @param string $itemId
     * @param string $pckId
     *
     * @return static
     */
    public static function createWithItemIdForPckId($itemId, $pckId)
    {
        return static::createWithItemId($itemId)->withPckId($pckId);
    }

    /**
     * Create an Item with an ItemId. (Not preferred method of doing this!)
     *
     * The usual case is that you would want to create an Item with a PckId as well,
     * using createWithItemIdForPckId, but in some cases it may be easier to have
     * PckId set using some other way. This is not the preferred way to create
     * an Item as it is possible for this item to be in an invalid state.
     *
     * @see Item::createWithItemIdForPckId
     *
     * @param string $itemId
     *
     * @return static
     */
    public static function createWithItemId($itemId)
    {
        return (new static())
            ->withItemId($itemId)
        ;
    }

    public function getPropertyNames()
    {
        return [
            'PckId',
            'ItemId',
            'ItemDescription',
            'CustomsDescription',
            'Quantity',
            'UnitPrice',
            'CountryOfOrigin',
            'HTSNumber',
        ];
    }
}
