<?php

namespace Gandung\PropertyAccessor\Tests;

use Gandung\PropertyAccessor\Getter\ObjectProperty;

class ObjectPropertyTest extends \PHPUnit_Framework_TestCase
{
	public function testCanGetInstance()
	{
		$objectGetter = new ObjectProperty();
		$this->assertInstanceOf(ObjectProperty::class, $objectGetter);
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testCanThrowExceptionWhenViolatingSetObject()
	{
		$objectGetter = new ObjectProperty();
		$this->assertInstanceOf(ObjectProperty::class, $objectGetter);
		$objectGetter->setObject(null);
	}

	public function testCanSetObjectWithAnObject()
	{
		$objectGetter = new ObjectProperty();
		$this->assertInstanceOf(ObjectProperty::class, $objectGetter);
		$objectGetter->setObject(new Fixtures\Foo);
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testCanThrowExceptionWhenViolatingSetProperty()
	{
		$objectGetter = new ObjectProperty();
		$this->assertInstanceOf(ObjectProperty::class, $objectGetter);
		$objectGetter->setProperty(null);
	}

	public function testCanSetPropertyNameWithPropertyName()
	{
		$objectGetter = new ObjectProperty();
		$this->assertInstanceOf(ObjectProperty::class, $objectGetter);
		$objectGetter->setProperty('data');
	}

	public function testCanGetPropertyName()
	{
		$objectGetter = new ObjectProperty();
		$this->assertInstanceOf(ObjectProperty::class, $objectGetter);
		$objectGetter->setObject(new Fixtures\Foo);
		$objectGetter->setProperty('data');
		$prop = $objectGetter->get();
		$this->assertNotNull($prop);
		$this->assertInternalType('string', $prop);
		$this->assertEquals('data', $prop);
	}
}