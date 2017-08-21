<?php

namespace Gandung\PropertyAccessor\Tests;

use Gandung\PropertyAccessor\Normalizer;

class NormalizerTest extends \PHPUnit_Framework_TestCase
{
	public function testCanGetInstance()
	{
		$normalizer = new Normalizer();
		$this->assertInstanceOf(Normalizer::class, $normalizer);
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testCanThrowExceptionWhenViolatingToCamelCase()
	{
		$normalizer = new Normalizer();
		$this->assertInstanceOf(Normalizer::class, $normalizer);
		$normalizer->toCamelCase(null);
	}
	
	public function testCanStandardizeToCamelCase()
	{
		$normalizer = new Normalizer();
		$this->assertInstanceOf(Normalizer::class, $normalizer);
		$normalized = $normalizer->toCamelCase('first_name');
		$this->assertNotNull($normalized);
		$this->assertInternalType('string', $normalized);
		$this->assertEquals('firstName', $normalized);
	}
}