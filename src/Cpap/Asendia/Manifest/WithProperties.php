<?php

namespace Cpap\Asendia\Manifest;

trait WithProperties
{
    /**
     * @var array
     */
    private $properties = [];

    /**
     * The expected property names.
     *
     * @see Properties::getPropertyNames
     *
     * @return string[]
     */
    abstract protected function getPropertyNames();

    /**
     * The property names that represent numeric properties.
     *
     * This is to allow us to ignore properties completely if they have null
     * value per the Asendia Global eFile XML Data Import Specification.
     *
     * @return string[]
     */
    protected function getNumericPropertyNames()
    {
        return [];
    }

    /**
     * An associate array of all properties as key value pairs.
     *
     * @see Properties::getProperties
     *
     * @return array
     */
    public function getProperties()
    {
        $properties = [];
        foreach ($this->getPropertyNames() as $propertyName) {
            $propertyValue = $this->getProperty($propertyName);

            if (in_array($propertyName, $this->getNumericPropertyNames()) && is_null($propertyValue)) {
                continue;
            }

            $propertyValue = $this->resolvePropertyValueForArray($propertyValue);
            $propertyValue = $this->resolvePropertyValueForObject($propertyValue);

            $properties[$propertyName] = $propertyValue;
        }

        return $properties;
    }

    /**
     * Get a property; if it does not exist, return a default value or null.
     *
     * @param string $propertyName
     * @param null $default
     *
     * @return mixed|null
     */
    protected function getProperty($propertyName, $default = null)
    {
        return isset($this->properties[$propertyName]) ? $this->properties[$propertyName] : $default;
    }

    /**
     * Set a property on a clone of an object.
     *
     * Used to allow for working directly with properties by classes that
     * use this trait. Allows for creating custom behavior for dealing
     * with special properties.
     *
     * @param string $propertyName
     * @param null $value
     *
     * @return static
     */
    protected function withProperty($propertyName, $value = null)
    {
        $instance = clone($this);

        $instance->properties[$propertyName] = $value;

        return $instance;
    }

    /**
     * Set a property on a clone of an object using with{PropertyName} conventions.
     *
     * Used to allow for working with properties by classes and users of classes.
     *
     * @param string $name
     * @param array $arguments
     *
     * @return WithProperties
     */
    public function __call($name, $arguments)
    {
        $this->guardArgumentCount($name, $arguments);
        $withPosition = $this->getWithPosition($name);
        $withKey = $this->getWithKey($name, $withPosition);

        $instance = clone($this);
        $instance->properties[$withKey] = $arguments[0];

        return $instance;
    }

    private function resolvePropertyValueForArray($propertyValue = null)
    {
        if (! is_array($propertyValue)) {
            return $propertyValue;
        }

        return array_map(function ($propertyValue) {
            return $this->resolvePropertyValueForObject($propertyValue);
        }, $propertyValue);
    }

    private function resolvePropertyValueForObject($propertyValue = null)
    {
        if (! is_object($propertyValue)) {
            return $propertyValue;
        }

        if (! $propertyValue instanceof Properties) {
            return $propertyValue;
        }

        return $propertyValue->getProperties();
    }

    private function getWithPosition($name)
    {
        $withPosition = strpos($name, 'with');

        $this->guardMethodNameStartsWithWith($name, $withPosition);

        return $withPosition;
    }

    private function getWithKey($name, $withPosition)
    {
        $this->guardWithMethodNameHasPropertyName($name, $withPosition);

        $propertyName = substr($name, $withPosition+4);

        $this->guardWithMethodNameHasValidPropertyName($name, $propertyName);

        return $propertyName;
    }

    private function guardArgumentCount($name, $arguments)
    {
        if (count($arguments) === 1) {
            return;
        }

        throw new \InvalidArgumentException('Method ' . $name . ' requires exactly one argument');
    }

    private function guardMethodNameStartsWithWith($name, $withPosition = null)
    {
        if (0 === $withPosition) {
            return;
        }

        $message = sprintf(
            'Method %s is not callable by this object (should start with "with...")',
            $name
        );

        throw new \BadMethodCallException($message);
    }

    private function guardWithMethodNameHasPropertyName($name, $withPosition)
    {
        if (strlen($name) > ($withPosition+4)) {
            return;
        }

        $message = sprintf(
            'Method %s is not callable by this object (expected PropertyName suffix)',
            $name
        );

        throw new \BadMethodCallException($message);
    }

    private function guardWithMethodNameHasValidPropertyName($name, $propertyName)
    {
        if (in_array($propertyName, $this->getPropertyNames())) {
            return;
        }

        $message = sprintf(
            'Method %s is not callable by this object (property %s is not in list of expected keys: %s)',
            $name,
            $propertyName,
            implode(', ', $this->getPropertyNames())
        );

        throw new \BadMethodCallException($message);
    }
}
