<?php

namespace Gandung\PropertyAccessor\Adapter;

use Symfony\Component\PropertyAccess\PropertyAccessorBuilder as SymfonyAccessorBuilder;

class SymfonyPropertyAccessor implements PropertyAccessorAdapter
{
    /**
     * @var SymfonyAccessor
     */
    private $symfonyAccessor;

    public function __construct(SymfonyAccessorBuilder $accessorBuilder)
    {
        $this->symfonyAccessor = $accessorBuilder->getPropertyAccessor();
    }

    public function getValue($handler, $keyOrPropertyPath, $force = false)
    {
        return $this->symfonyAccessor->getValue($handler, $keyOrPropertyPath);
    }

    public function setValue(&$handler, $keyOrPropertyPath, $value, $force = false)
    {
        $this->symfonyAccessor->setValue($handler, $keyOrPropertyPath, $value);
    }

    public function getAdapter()
    {
        return $this->symfonyAccessor;
    }
}
