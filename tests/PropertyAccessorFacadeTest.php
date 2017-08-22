<?php

namespace Gandung\PropertyAccessor\Tests;

use Gandung\PropertyAccessor\PropertyAccessor;
use Gandung\PropertyAccessor\PropertyAccessorFacade;

class PropertyAccessorFacadeTest extends \PHPUnit_Framework_TestCase
{
	public function testCanGetAccessor()
	{
		$accessor = PropertyAccessorFacade::getAccessor();
		$this->assertInstanceOf(PropertyAccessor::class, $accessor);
	}
}