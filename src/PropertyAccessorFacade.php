<?php

namespace Gandung\PropertyAccessor;

use Gandung\PropertyAccessor\PropertyAccessor;
use Gandung\PropertyAccessor\Normalizer;
use Gandung\PropertyAccessor\Getter\ArrayKey;
use Gandung\PropertyAccessor\Getter\ObjectProperty;

class PropertyAccessorFacade
{
	/**
	 * This class cannot be instantiated.
	 */
	private function __construct()
	{

	}

	public static function getAccessor()
	{
		return new PropertyAccessor(new Normalizer, new ArrayKey, new ObjectProperty);
	}
}