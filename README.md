# Property Accessor Library In PHP

Highly inspired by symfony property access component. This library provides function to
read and write from/to an object or array using a simple string expression. Enforce mode
supported.

## Features:

- Reading/Writing from/to array.
- Reading/Writing from/to object instance using magic __set/__get method.
- Reading/Writing from/to object instance using setter/getter method.
- Reading/Writing from/to object instance in enforce mode to bypass protected/private access modifier.

## Reading/Writing from/to array.

## Reading From Array

### One dimensional array

```php
use Gandung\PropertyAccessor\PropertyAccessorFacade;

$list = ['first-name' => 'Paulus', 'middle-name' => 'Gandung', 'last-name' => 'Prakosa'];
$accessor = PropertyAccessorFacade::getAccessor();
$value = $accessor->getValue($list, '[first-name]');

echo sprintf("%s\n", $value);
```

### Multi dimensional array

```php
use Gandung\PropertyAccessor\PropertyAccessorFacade;

$list = [
	['first-name' => 'Paulus', 'middle-name' => 'Gandung', 'last-name' => 'Prakosa'],
	['first-name' => 'Florensia', 'middle-name' => 'Agnes', 'last-name' => 'Paskaliani'],
	['first-name' => 'Achmad', 'middle-name' => 'Muchlis', 'last-name' => 'Fanani']
];
$accessor = PropertyAccessorFacade::getAccessor();
$value = $accessor->getValue($list, '[1][middle-name]');

echo sprintf("%s\n", $value);
```

## Writing to array

### One dimensional array

```php
use Gandung\PropertyAccessor\PropertyAccessorFacade;

$list = ['first-name' => 'Paulus', 'middle-name' => 'Gandung', 'last-name' => 'Prakosa'];
$accessor = PropertyAccessorFacade::getAccessor();
$accessor->setValue($list, '[first-name]', 'Benedictus');
$value = $accessor->getValue($list, '[first-name]');

echo sprintf("%s\n", $value);
```

### Multi dimensional array

```php
use Gandung\PropertyAccessor\PropertyAccessorFacade;

$list = [
	['first-name' => 'Paulus', 'middle-name' => 'Gandung', 'last-name' => 'Prakosa'],
	['first-name' => 'Florensia', 'middle-name' => 'Agnes', 'last-name' => 'Paskaliani'],
	['first-name' => 'Achmad', 'middle-name' => 'Muchlis', 'last-name' => 'Fanani']
];
$accessor = PropertyAccessorFacade::getAccessor();
$accessor->setValue($list, '[1][first-name]', 'Yohana');
$value = $accessor->getValue($list, '[1][first-name]');

echo sprintf("%s\n", $value);
```

## Reading/Writing from/to object instance using magic __set/__get method.

Assume i have one class whose act as a fixture.

```php
namespace Gandung\PropertyAccess\Tests\Fixtures;

use Gandung\PropertyAccess\Exception\ObjectPropertyNotExistException;

class Foo
{
	/**
	 * @var string
	 */
	private $data;

	/**
	 * @var string
	 */
	public $publicData;

	/**
	 * Setter
	 */
	public function setData($data)
	{
		$this->data = $data;
	}

	/**
	 * Getter
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * Reference: http://php.net/manual/en/language.oop5.overloading.php#object.set
	 */
	public function __set($name, $value)
	{
		if (!property_exists($this, $name)) {
			throw new ObjectPropertyNotExistException(
				sprintf("'%s' doesn't have property named '%s'", get_class($this), $name)
			);
		}

		$this->{$name} = $value;
	}

	/**
	 * Reference: http://php.net/manual/en/language.oop5.overloading.php#object.get
	 */
	public function __get($name)
	{
		if (!property_exists($this, $name)) {
			throw new ObjectPropertyNotExistException(
				sprintf("'%s' doesn't have property named '%s'", get_class($this), $name)
			);
		}

		return $this->{$name};
	}
}
```

### Reading from object instance using __get magic method

```php
use Gandung\PropertyAccessor\PropertyAccessorFacade;
use Gandung\PropertyAccessor\Tests\Fixtures\Foo;

$foo = new Foo;
$accessor = PropertyAccessorFacade::getAccessor()->enableMagicMethod();
$value = $accessor->getValue($foo, 'data');

echo sprintf("%s\n", is_null($value) ? "null" : $value);
```

This will outputting "null" because no data has been written to object property named 'data'

### Writing to object instance using __set magic method
```php
use Gandung\PropertyAccessor\PropertyAccessorFacade;
use Gandung\PropertyAccessor\Tests\Fixtures\Foo;

$foo = new Foo;
$accessor = PropertyAccessorFacade::getAccessor()->enableMagicMethod();
$accessor->setValue($foo, 'data', 'this is a text.');
$value = $accessor->getValue($foo, 'data');

echo sprintf("%s\n", is_null($value) ? "null" : $value);
```

This will outputting "this is a text.".

PR are always welcome :))