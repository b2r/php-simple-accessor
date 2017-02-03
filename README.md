b2rPHP: SimpleAccessor
======================

Simple accessor trait

[![Build Status](https://travis-ci.org/b2r/php-simple-accessor.svg?branch=master)](https://travis-ci.org/b2r/php-simple-accessor)

- [CHANGELOG](CHANGELOG.md)

Traits
------
- Getter
- Setter
- Accessor: Getter + Setter

Basic Usage
-----------
```php
use b2r\Component\SimpleAccessor\Accessor;

class Foo
{
    use Accessor;

    private $value = 0;

    public function getSquare()
    {
        return $this->value * $this->value;
    }

    public function setValue($value)
    {
        $this->value = (int)$value;
    }
}

$foo = new Foo();
$foo->value = '10'; // Invoke `setValue()`
var_dump($foo->value); #=>int(10)
var_dump($foo->square); #=>int(100) Invoke `getSquare()`

//$foo->undefined = 'Undefined'; #=> throw b2r\Component\Exception\InvalidPropertyException
```

Getter::getPropertyValue
------------------------

Getter core method

`protected function getPropertyValue($name, array $options = null)`

```php
$options = [
    'default'  => null,    # Undefined property default value
    'prefixes' => ['get'], # Getter method prefixes
    'read'     => true,    # Read private/protected property?
    'strict'   => true,    # Thorw exception?
]
```

Setter::setPropertyValue
------------------------

Setter core method

`protected function setPropertyValue($name, $value, array $options = null)`

```php
$options = [
    'create'    => false,   # Create undefined property?
    'immutable' => false,   # Immutable?
    'prefixes'  => ['set'], # Setter method prefixes
    'update'    => false,   # Update private/protected property?
    'strict'    => true,    # Thorw exception?
]
```

More samples
--------------------

### Loose

Do not throw exception

```php
use b2r\Component\SimpleAccessor\Accessor;

class Foo
{
    use Accessor;

    private $value = 0;

    public function __get($name)
    {
        return $this->getPropertyValue($name, [
            'strict'  => false,
            'default' => 'Default value for undefined property'
        ]);
    }
}

$foo = new Foo();
var_dump($foo->undefined); #=>"Default value for undefined property"
```

### Immutable

Immutable (write once)

```php

use b2r\Component\SimpleAccessor\Accessor;

class Foo
{
    use Accessor;

    private $value = 0;

    public function __set($name, $value)
    {
        return $this->setPropertyValue($name, $value, ['immutable' => true]);
    }

    protected function setValue($value)
    {
        $this->value = (int)$value;
    }
}

$foo = new Foo();
$foo->value = '10'; # Invoke `setValue()`
var_dump($foo->value); #=>int(10)
$foo->value = 100; #=>b2r\Component\Exception\InvalidPropertyException: Invalid property Foo::$value is immutable
```
