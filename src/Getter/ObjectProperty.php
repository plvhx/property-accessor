<?php

namespace Gandung\PropertyAccessor\Getter;

class ObjectProperty implements GetterInterface
{
    /**
     * @var object
     */
    private $object;

    /**
     * @var string
     */
    private $property;

    public function setObject($obj)
    {
        if (!is_object($obj)) {
            throw new \InvalidArgumentException(
                sprintf("Parameter 1 of %s must be a valid class instance.", __METHOD__)
            );
        }

        $this->object = $obj;
    }

    public function setProperty($property)
    {
        if (!is_string($property)) {
            throw new \InvalidArgumentException(
                sprintf("Parameter 1 of %s must be a string.", __METHOD__)
            );
        }

        $this->property = $property;
    }

    public function get()
    {
        $refl = new \ReflectionClass($this->object);

        return $refl->hasProperty($this->property)
            ? $this->property
            : null;
    }
}
