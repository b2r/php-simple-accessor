<?php

namespace b2r\Component\SimpleAccessor;

use b2r\Component\Exception\InvalidPropertyException;

/**
 * Simple property setter trait
 */
trait Setter
{
    public function __set($name, $value)
    {
        return $this->setPropertyValue($name, $value);
    }

    /**
     * Set property value
     *
     * #### $options defaults
     * ```php
     * $options = [
     *     'create'    => false,   # Create undefined property?
     *     'immutable' => false,   # Immutable?
     *     'prefixes'  => ['set'], # Setter method prefixes
     *     'update'    => false,   # Update private/protected property?
     *     'strict'    => true,    # Thorw exception?
     * ]
     * ```
     * @param string $name
     * @param mixed $value
     * @param array|null $options
     */
    protected function setPropertyValue($name, $value, array $options = null)
    {
        // Prepare options
        $o = [
            'create'    => false,
            'immutable' => false,
            'prefixes'  => ['set'],
            'strict'    => true,
            'update'    => false,
        ];
        if ($options) {
            $o = array_merge($o, $options);
        }

        // Immutable
        if ($o['immutable'] && property_exists($this, $name) && $this->$name !== null) {
            throw new InvalidPropertyException($this, $name . ' is immutable');
        }

        // Setter
        foreach ($o['prefixes'] as $prefix) {
            $method = $prefix . $name;
            if (method_exists($this, $method)) {
                return $this->$method($value);
            }
        }

        // Create / Update
        if ($o['create'] || ($o['update'] && property_exists($this, $name))) {
            $this->$name = $value;
            return $this;
        }

        // Exception
        if ($o['strict']) {
            throw new InvalidPropertyException($this, $name);
        }

        return $this;
    }
}
