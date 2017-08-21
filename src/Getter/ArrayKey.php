<?php

namespace Gandung\PropertyAccessor\Getter;

class ArrayKey implements GetterInterface
{
    /**
     * @var string
     */
    private $index = [];

    public function parse($expr)
    {
        $normalized = preg_replace(
            '/(\[|\])/',
            '',
            preg_replace('/(\]\[)/', '|', $expr)
        );
        
        $this->index = explode('|', $normalized);
    }

    public function get()
    {
        return $this->index;
    }
}
