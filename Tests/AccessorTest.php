<?php

namespace b2r\Component\SimpleAccessor;

require_once __DIR__ . '/../vendor/autoload.php';

class Foo
{
    use Accessor;

    protected $id = 0;
    protected $name = '';

    public function get($name, array $options = [])
    {
        return $this->getPropertyValue($name, $options);
    }

    public function set($name, $value, array $options = [])
    {
        return $this->setPropertyValue($name, $value, $options);
    }

    public function getName()
    {
        return $this->name;
    }

    public function setId($value)
    {
        $this->id = (int)$value;
    }
}

/**
 * Basic UnitTest class
 */
class GetterTest extends \PHPUnit\Framework\TestCase
{
    public function setup()
    {
        $this->foo = new Foo();
    }

    public function testBasic()
    {
        $foo = $this->foo;
        $this->assertEquals(0, $foo->id);
        $this->assertEquals('', $foo->name);

        $foo->id = 100;
        $this->assertEquals(100, $foo->id);
    }

    public function testGetDefault()
    {
        $foo = $this->foo;
        $ex = 'Undefined property';
        $this->assertEquals($ex, $foo->get('hoge', ['strict' => false, 'default' => $ex]));
    }

    public function testSetDirect()
    {
        $foo = $this->foo;
        $ex = 'Set value';
        $foo->set('name', $ex, ['update' => true]);
        $this->assertEquals($ex, $foo->name);
    }

    public function testSetDefault()
    {
        $foo = $this->foo;
        $val = $foo->set('hoge', 'value', ['strict' => false]);
        $this->assertEquals($foo, $val);
    }

    /**
     * @expectedException \b2r\Component\Exception\InvalidPropertyException
     */
    public function testGetException()
    {
        $this->foo->hoge;
    }

    /**
     * @expectedException \b2r\Component\Exception\InvalidPropertyException
     */
    public function testSetException()
    {
        $this->foo->name = 'Hoge';
    }

    /**
     * @expectedException \b2r\Component\Exception\InvalidPropertyException
     */
    public function testImmutableException()
    {
        $foo = $this->foo;
        $options = ['immutable' => true];
        $foo->set('id', 1, $options); // Can update
        $this->assertEquals(1,  $foo->id);
        $foo->set('id', 2, $options); // Cannot update
    }
}
