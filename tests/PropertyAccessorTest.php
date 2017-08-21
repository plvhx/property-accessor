<?php

namespace Gandung\PropertyAccessor\Tests;

use Gandung\PropertyAccessor\PropertyAccessor;
use Gandung\PropertyAccessor\Normalizer;
use Gandung\PropertyAccessor\Getter\ArrayKey;
use Gandung\PropertyAccessor\Getter\ObjectProperty;
use Gandung\PropertyAccessor\Exception\UndefinedIndexOrOffsetException;

class PropertyAccessorTest extends \PHPUnit_Framework_TestCase
{
	public function testCanGetInstance()
	{
		$accessor = new PropertyAccessor(new Normalizer, new ArrayKey, new ObjectProperty);
		$this->assertInstanceOf(PropertyAccessor::class, $accessor);
	}

	public function testCanEnableMagicMethodFeature()
	{
		$accessor = new PropertyAccessor(new Normalizer, new ArrayKey, new ObjectProperty);
		$this->assertInstanceOf(PropertyAccessor::class, $accessor);
		$accessor->enableMagicMethod();
	}

	public function testCanDisableMagicMethodFeature()
	{
		$accessor = new PropertyAccessor(new Normalizer, new ArrayKey, new ObjectProperty);
		$this->assertInstanceOf(PropertyAccessor::class, $accessor);
		$accessor->disableMagicMethod();
	}

	public function testCanGetValueFromOneDimensionArray()
	{
		$fixtures = ['first-name' => 'Paulus', 'middle-name' => 'Gandung', 'last-name' => 'Prakosa'];
		$accessor = new PropertyAccessor(new Normalizer, new ArrayKey, new ObjectProperty);
		$this->assertInstanceOf(PropertyAccessor::class, $accessor);
		$value = $accessor->getValue($fixtures, '[first-name]');
		$this->assertNotNull($value);
		$this->assertInternalType('string', $value);
		$this->assertEquals('Paulus', $value);
	}

	/**
	 * @expectedException Gandung\PropertyAccessor\Exception\UndefinedIndexOrOffsetException
	 */
	public function testCanThrowExceptionWhenTryingToGetValueFromOneDimensionalArray()
	{
		$fixtures = ['first-name' => 'Paulus', 'middle-name' => 'Gandung', 'last-name' => 'Prakosa'];
		$accessor = new PropertyAccessor(new Normalizer, new ArrayKey, new ObjectProperty);
		$this->assertInstanceOf(PropertyAccessor::class, $accessor);
		$accessor->getValue($fixtures, '[nonexistent-key]');
	}

	public function testCanGetValueFromTwoDimensionalArray()
	{
		$fixtures = [
			['first-name' => 'Paulus', 'middle-name' => 'Gandung', 'last-name' => 'Prakosa'],
			['first-name' => 'Florensia', 'middle-name' => 'Agnes', 'last-name' => 'Paskaliani'],
			['first-name' => 'Achmad', 'middle-name' => 'Muchlis', 'last-name' => 'Fanani']
		];
		$accessor = new PropertyAccessor(new Normalizer, new ArrayKey, new ObjectProperty);
		$this->assertInstanceOf(PropertyAccessor::class, $accessor);
		$value = $accessor->getValue($fixtures, '[0][first-name]');
		$this->assertNotNull($value);
		$this->assertInternalType('string', $value);
		$this->assertEquals('Paulus', $value);
	}

	/**
	 * @expectedException Gandung\PropertyAccessor\Exception\UndefinedIndexOrOffsetException
	 */
	public function testCanThrowExceptionWhenTryingToGetValueFromTwoDimensionalArray()
	{
		$fixtures = [
			['first-name' => 'Paulus', 'middle-name' => 'Gandung', 'last-name' => 'Prakosa'],
			['first-name' => 'Florensia', 'middle-name' => 'Agnes', 'last-name' => 'Paskaliani'],
			['first-name' => 'Achmad', 'middle-name' => 'Muchlis', 'last-name' => 'Fanani']
		];
		$accessor = new PropertyAccessor(new Normalizer, new ArrayKey, new ObjectProperty);
		$this->assertInstanceOf(PropertyAccessor::class, $accessor);
		$accessor->getValue($fixtures, '[0][nonexistent-key]');
	}

	public function testCanGetValueFromObjectWhosePropertyIsPublic()
	{
		$fixtures = new Fixtures\Bar;
		$this->assertInstanceOf(Fixtures\Bar::class, $fixtures);
		$accessor = new PropertyAccessor(new Normalizer, new ArrayKey, new ObjectProperty);
		$this->assertInstanceOf(PropertyAccessor::class, $accessor);
		$accessor->setValue($fixtures, 'data', 'this is a text.');
		$value = $accessor->getValue($fixtures, 'data');
		$this->assertNotNull($value);
		$this->assertInternalType('string', $value);
		$this->assertEquals('this is a text.', $value);
	}

	public function testCanGetValueFromObjectWithDefaultObjectGetter()
	{
		$fixtures = new Fixtures\Foo;
		$this->assertInstanceOf(Fixtures\Foo::class, $fixtures);
		$accessor = new PropertyAccessor(new Normalizer, new ArrayKey, new ObjectProperty);
		$this->assertInstanceOf(PropertyAccessor::class, $accessor);
		$accessor->setValue($fixtures, 'data', 'this is a text.');
		$value = $accessor->getValue($fixtures, 'data');
		$this->assertNotNull($value);
		$this->assertInternalType('string', $value);
		$this->assertEquals('this is a text.', $value);
	}

	public function testCanGetValueFromObjectWithMagicMethodFeatureEnabled()
	{
		$fixtures = new Fixtures\Foo;
		$this->assertInstanceOf(Fixtures\Foo::class, $fixtures);
		$accessor = new PropertyAccessor(new Normalizer, new ArrayKey, new ObjectProperty);
		$this->assertInstanceOf(PropertyAccessor::class, $accessor);
		$accessor
			->enableMagicMethod()
			->setValue($fixtures, 'data', 'this is a text.');
		$value = $accessor->getValue($fixtures, 'data');
		$this->assertNotNull($value);
		$this->assertInternalType('string', $value);
		$this->assertEquals('this is a text.', $value);
	}

	/**
	 * @expectedException \LogicException
	 */
	public function testCanThrowExceptionDueToNonexistentGetMagicMethod()
	{
		$fixtures = new Fixtures\FooWithoutMagicMethod;
		$this->assertInstanceOf(Fixtures\FooWithoutMagicMethod::class, $fixtures);
		$accessor = new PropertyAccessor(new Normalizer, new ArrayKey, new ObjectProperty);
		$this->assertInstanceOf(PropertyAccessor::class, $accessor);
		$accessor
			->enableMagicMethod()
			->getValue($fixtures, 'data');
	}

	/**
	 * @expectedException \LogicException
	 */
	public function testCanThrowExceptionDueToNonexistentSetMagicMethod()
	{
		$fixtures = new Fixtures\FooWithoutMagicMethod;
		$this->assertInstanceOf(Fixtures\FooWithoutMagicMethod::class, $fixtures);
		$accessor = new PropertyAccessor(new Normalizer, new ArrayKey, new ObjectProperty);
		$this->assertInstanceOf(PropertyAccessor::class, $accessor);
		$accessor
			->enableMagicMethod()
			->setValue($fixtures, 'data', 'this is a text.');
	}

	/**
	 * @expectedException \LogicException
	 */
	public function testCanThrowExceptionDueToNonexistentObjectGetterMethod()
	{
		$fixtures = new Fixtures\BarWithoutSetterAndGetter;
		$this->assertInstanceOf(Fixtures\BarWithoutSetterAndGetter::class, $fixtures);
		$accessor = new PropertyAccessor(new Normalizer, new ArrayKey, new ObjectProperty);
		$this->assertInstanceOf(PropertyAccessor::class, $accessor);
		$accessor->getValue($fixtures, 'data');
	}

	/**
	 * @expectedException \LogicException
	 */
	public function testCanThrowExceptionDueToNonexistentObjectSetterMethod()
	{
		$fixtures = new Fixtures\BarWithoutSetterAndGetter;
		$this->assertInstanceOf(Fixtures\BarWithoutSetterAndGetter::class, $fixtures);
		$accessor = new PropertyAccessor(new Normalizer, new ArrayKey, new ObjectProperty);
		$this->assertInstanceOf(PropertyAccessor::class, $accessor);
		$accessor->setValue($fixtures, 'data', 'this is a shit.');
	}

	public function testCanGetValueFromObjectInForceMode()
	{
		$fixtures = new Fixtures\FooWithoutMagicMethod;
		$this->assertInstanceOf(Fixtures\FooWithoutMagicMethod::class, $fixtures);
		$accessor = new PropertyAccessor(new Normalizer, new ArrayKey, new ObjectProperty);
		$this->assertInstanceOf(PropertyAccessor::class, $accessor);
		$accessor->setValue($fixtures, 'data', 'this is a text.', true);
		$value = $accessor->getValue($fixtures, 'data', true);
		$this->assertNotNull($value);
		$this->assertInternalType('string', $value);
		$this->assertEquals('this is a text.', $value);
	}

	public function testCanSetValueToTwoDimensionalArray()
	{
		$fixtures = [
			['first-name' => 'Paulus', 'middle-name' => 'Gandung', 'last-name' => 'Prakosa'],
			['first-name' => 'Florensia', 'middle-name' => 'Agnes', 'last-name' => 'Paskaliani'],
			['first-name' => 'Achmad', 'middle-name' => 'Muchlis', 'last-name' => 'Fanani']
		];
		$accessor = new PropertyAccessor(new Normalizer, new ArrayKey, new ObjectProperty);
		$this->assertInstanceOf(PropertyAccessor::class, $accessor);
		$value = $accessor->getValue($fixtures, '[0][first-name]');
		$this->assertNotNull($value);
		$this->assertInternalType('string', $value);
		$this->assertEquals('Paulus', $value);
		$accessor->setValue($fixtures, '[0][first-name]', 'Benedictus');
		$value = $accessor->getValue($fixtures, '[0][first-name]');
		$this->assertNotNull($value);
		$this->assertInternalType('string', $value);
		$this->assertEquals('Benedictus', $value);
	}

	/**
	 * @expectedException Gandung\PropertyAccessor\Exception\UndefinedIndexOrOffsetException
	 */
	public function testCanThrowExceptionWhenTryingToSetValueFromOneDimensionalArray()
	{
		$fixtures = ['first-name' => 'Paulus', 'middle-name' => 'Gandung', 'last-name' => 'Prakosa'];
		$accessor = new PropertyAccessor(new Normalizer, new ArrayKey, new ObjectProperty);
		$this->assertInstanceOf(PropertyAccessor::class, $accessor);
		$accessor->setValue($fixtures, '[nonexistent-key]', 'this is a text.');
	}

	/**
	 * @expectedException Gandung\PropertyAccessor\Exception\UndefinedIndexOrOffsetException
	 */
	public function testCanThrowExceptionWhenTryingToSetValueFromTwoDimensionalArray()
	{
		$fixtures = [
			['first-name' => 'Paulus', 'middle-name' => 'Gandung', 'last-name' => 'Prakosa'],
			['first-name' => 'Florensia', 'middle-name' => 'Agnes', 'last-name' => 'Paskaliani'],
			['first-name' => 'Achmad', 'middle-name' => 'Muchlis', 'last-name' => 'Fanani']
		];
		$accessor = new PropertyAccessor(new Normalizer, new ArrayKey, new ObjectProperty);
		$this->assertInstanceOf(PropertyAccessor::class, $accessor);
		$accessor->setValue($fixtures, '[0][nonexistent-key]', 'this is a text.');
	}
}