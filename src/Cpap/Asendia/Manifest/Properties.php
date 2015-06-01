<?php

namespace Cpap\Asendia\Manifest;

interface Properties
{
    /**
     * The expected property names.
     *
     * @return string[]
     */
    function getPropertyNames();

    /**
     * An associate array of all properties as key value pairs.
     *
     * Any value that is an instance of Properties will have
     * getProperties called on it to flatten those objects.
     *
     * For any value that is an array, any object in the
     * array that is an instance of Properties will
     * getProperties called on it to flatten those
     * objects.
     *
     * @return array
     */
    function getProperties();
}
