<?php

namespace b2r\Component\SimpleAccessor;

use b2r\Component\Exception\InvalidPropertyException;

/**
 * Simple property getter trait
 */
trait Getter
{
    public function __get($name)
    {
        return $this->getPropertyValue($name);
    }

    /**
     * Get property value
     *
     * #### $options defaults
     * ```php
     * $options = [
     *     'default'  => null,    # Undefined property default value
     *     'prefixes' => ['get'], # Getter method prefixes
     *     'read'     => true,    # Read private/protected property?
     *     'strict'   => true,    # Thorw exception?
     * ]
     * ```
     * @param string $name
     * @param array|null $options
     */
    protected function getPropertyValue($name, array $options = null)
    {
        $o = [
            'default'  => null,
            'prefixes' => ['get'],
            'read'     => true,
            'strict'   => true,
        ];
        if ($options) {
            $o = array_merge($o, $options);
        }

        foreach ($o['prefixes'] as $prefix) {
            $method = $prefix . $name;
            if (method_exists($this, $method)) {
                return $this->$method();
            }
        }

        if ($o['read'] && property_exists($this, $name)) {
            return $this->$name;
        }

        if ($o['strict']) {
            throw new InvalidPropertyException($this, $name);
        }

        return $o['default'];
    }
}
