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

PR are always welcome :))