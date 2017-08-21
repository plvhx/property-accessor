<?php

namespace Gandung\PropertyAccessor\Tests;

use Gandung\PropertyAccessor\Adapter\SymfonyPropertyAccessor;
use Symfony\Component\PropertyAccess\PropertyAccessorBuilder as SymfonyAccessorBuilder;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class SymfonyPropertyAccessorTest extends \PHPUnit_Framework_TestCase
{
	public function testCanGetInstance()
	{
		$accessor = new SymfonyPropertyAccessor(new SymfonyAccessorBuilder);
		$this->assertInstanceOf(SymfonyPropertyAccessor::class, $accessor);
	}

	public function testCanGetValueOfOneDimensionalArray()
	{
		$accessor = new SymfonyPropertyAccessor(new SymfonyAccessorBuilder);
		$this->assertInstanceOf(SymfonyPropertyAccessor::class, $accessor);
		$fixtures = range(1, 10);
		$index = (string)(rand(0, sizeof($fixtures) - 1));
		$this->assertNotNull($accessor->getValue($fixtures, '[' . $index . ']'));
		$this->assertInternalType('integer', $accessor->getValue($fixtures, '[' . $index . ']'));
	}

	public function testCanSetValueOfOneDimensionalArray()
	{
		$accessor = new SymfonyPropertyAccessor(new SymfonyAccessorBuilder);
		$this->assertInstanceOf(SymfonyPropertyAccessor::class, $accessor);
		$fixtures = range(1, 10);
		$index = (string)(rand(0, sizeof($fixtures) - 1));
		$this->assertNotNull($accessor->getValue($fixtures, '[' . $index . ']'));
		$this->assertInternalType('integer', $accessor->getValue($fixtures, '[' . $index . ']'));
		$val = 1337;
		$accessor->setValue($fixtures, '[' . $index . ']', $val);
		$this->assertNotNull($accessor->getValue($fixtures, '[' . $index . ']'));
		$this->assertInternalType('integer', $accessor->getValue($fixtures, '[' . $index . ']'));
		$this->assertEquals($val, $accessor->getValue($fixtures, '[' . $index . ']'));
	}

	public function testCanGetValueOfTwoDimensionalArray()
	{
		$fixtures = [
			['first-name' => 'Paulus', 'middle-name' => 'Gandung', 'last-name' => 'Prakosa'],
			['first-name' => 'Florensia', 'middle-name' => 'Agnes', 'last-name' => 'Paskaliani'],
			['first-name' => 'Achmad', 'middle-name' => 'Muchlis', 'last-name' => 'Fanani']
		];
		$accessor = new SymfonyPropertyAccessor(new SymfonyAccessorBuilder);
		$this->assertInstanceOf(SymfonyPropertyAccessor::class, $accessor);
		$this->assertNotNull($accessor->getValue($fixtures, '[0][first-name]'));
		$this->assertInternalType('string', $accessor->getValue($fixtures, '[0][first-name]'));
	}

	public function testCanSetValueOfTwoDimensionalArray()
	{
		$fixtures = [
			['first-name' => 'Paulus', 'middle-name' => 'Gandung', 'last-name' => 'Prakosa'],
			['first-name' => 'Florensia', 'middle-name' => 'Agnes', 'last-name' => 'Paskaliani'],
			['first-name' => 'Achmad', 'middle-name' => 'Muchlis', 'last-name' => 'Fanani']
		];
		$accessor = new SymfonyPropertyAccessor(new SymfonyAccessorBuilder);
		$this->assertInstanceOf(SymfonyPropertyAccessor::class, $accessor);
		$this->assertNotNull($accessor->getValue($fixtures, '[0][first-name]'));
		$this->assertInternalType('string', $accessor->getValue($fixtures, '[0][first-name]'));
		$accessor->setValue($fixtures, '[0][first-name]', 'Benedictus');
		$this->assertNotNull($accessor->getValue($fixtures, '[0][first-name]'));
		$this->assertInternalType('string', $accessor->getValue($fixtures, '[0][first-name]'));
	}

	public function testCanGetValueOfSingleObjectFixture()
	{
		$fixtures = new Fixtures\Foo;
		$this->assertInstanceOf(Fixtures\Foo::class, $fixtures);
		$accessor = new SymfonyPropertyAccessor(new SymfonyAccessorBuilder);
		$this->assertInstanceOf(SymfonyPropertyAccessor::class, $accessor);
		$this->assertNull($accessor->getValue($fixtures, 'data'));
		$accessor->setValue($fixtures, 'data', 'this is a text.');
		$this->assertNotNull($accessor->getValue($fixtures, 'data'));
		$this->assertInternalType('string', $accessor->getValue($fixtures, 'data'));
		$this->assertEquals('this is a text.', $accessor->getValue($fixtures, 'data'));
	}

	public function testCanSetValueOfSingleObjectFixture()
	{
		$fixtures = new Fixtures\Foo;
		$this->assertInstanceOf(Fixtures\Foo::class, $fixtures);
		$accessor = new SymfonyPropertyAccessor(new SymfonyAccessorBuilder);
		$this->assertInstanceOf(SymfonyPropertyAccessor::class, $accessor);
		$this->assertNull($accessor->getValue($fixtures, 'data'));
		$accessor->setValue($fixtures, 'data', 'this is a text.');
		$this->assertNotNull($accessor->getValue($fixtures, 'data'));
		$this->assertInternalType('string', $accessor->getValue($fixtures, 'data'));
		$this->assertEquals('this is a text.', $accessor->getValue($fixtures, 'data'));	
	}

	public function testCanGetAdapter()
	{
		$accessor = new SymfonyPropertyAccessor(new SymfonyAccessorBuilder);
		$this->assertInstanceOf(SymfonyPropertyAccessor::class, $accessor);
		$this->assertInstanceOf(PropertyAccessor::class, $accessor->getAdapter());
	}
}