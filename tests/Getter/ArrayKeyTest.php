<?php

namespace Gandung\PropertyAccessor\Tests;

use Gandung\PropertyAccessor\Getter\ArrayKey;

class ArrayKeyTest extends \PHPUnit_Framework_TestCase
{
	public function testCanGetInstance()
	{
		$keyGetter = new ArrayKey();
		$this->assertInstanceOf(ArrayKey::class, $keyGetter);
	}

	public function testCanParseArrayKeyExpression()
	{
		$keyGetter = new ArrayKey();
		$this->assertInstanceOf(ArrayKey::class, $keyGetter);
		$keyGetter->parse('[0]');
		$this->assertNotNull($keyGetter->get());
		$this->assertInternalType('array', $keyGetter->get());
		$keyGetter->parse('[0][first-name]');
		$this->assertNotNull($keyGetter->get());
		$this->assertInternalType('array', $keyGetter->get());
	}
}