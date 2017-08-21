<?php

@trigger_error("This file is just for dummy testing purpose. Use proper unit test framework like PHPUnit and you're set.", E_USER_DEPRECATED);

require_once __DIR__ . DIRECTORY_SEPARATOR . 'vendor/autoload.php';

use Gandung\PropertyAccessor\Normalizer;
use Gandung\PropertyAccessor\PropertyAccessor;
use Gandung\PropertyAccessor\Getter\ArrayKey;
use Gandung\PropertyAccessor\Getter\ObjectProperty;

$q = new PropertyAccessor(
	new Normalizer, new ArrayKey, new ObjectProperty
);

if (!($q instanceof PropertyAccessor)) {
	die("\$q is not an instance of 'PropertyAccessor' class." . PHP_EOL);
}

$arr = new \stdClass;
$arr->numericTuple = array_map(function($q) { return ($q * 2); }, range(1, 10));
$arr->associationTuple = array_combine(['first-name', 'middle-name', 'last-name'], ['Paulus', 'Gandung', 'Prakosa']);
$arr->twoDimension = [
	['first-name' => 'Paulus', 'middle-name' => 'Gandung', 'last-name' => 'Prakosa'],
	['first-name' => 'Achmad', 'middle-name' => 'Angga', 'last-name' => 'Saputra'],
	['first-name' => 'Achmad', 'middle-name' => 'Muchlis', 'last-name' => 'Fanani']
];

class PropertyNotExistException extends \Exception
{

}

class Foo
{
	/**
	 * @var string
	 */
	private $data = 'this is a text.';

	public function setData($data)
	{
		$this->data = $data;
	}

	public function getData()
	{
		return $this->data;
	}
}

$foo = new Foo();

if (!($foo instanceof Foo)) {
	die(PHP_EOL);
}

echo $q->getValue($arr->numericTuple, '[' . rand(0, sizeof($arr->numericTuple) - 1) . ']') . PHP_EOL;
echo $q->getValue($arr->associationTuple, '[first-name]') . PHP_EOL;
echo $q->getValue($arr->twoDimension, '[2][middle-name]') . PHP_EOL;
echo $q->getValue($foo, 'data') . PHP_EOL;

$q->setValue($foo, 'data', 'paulus gandung prakosa');
$q->setValue($arr->twoDimension, '[0][first-name]', 'Benedictus');

echo $q->getValue($foo, 'data') . PHP_EOL;
echo $q->getValue($arr->twoDimension, '[0][first-name]') . PHP_EOL;