<?php

namespace Gandung\PropertyAccessor\Tests\Fixtures;

use Gandung\PropertyAccessor\Exception\ObjectPropertyNotExistException;

class Foo
{
	private $data;

	public function setData($data)
	{
		$this->data = $data;
	}

	public function getData()
	{
		return $this->data;
	}

	public function __set($name, $value)
	{
		if (!property_exists($this, $name)) {
			throw new ObjectPropertyNotExistException(
				sprintf("Unable to set value into property '%s' due to nonexistent property.", $name)
			);
		}

		$this->{$name} = $value;
	}

	public function __get($name)
	{
		if (!property_exists($this, $name)) {
			throw new ObjectPropertyNotExistException(
				sprintf("Unable to get value from property '%s' due to nonexistent property.", $name)
			);
		}

		return $this->{$name};
	}
}