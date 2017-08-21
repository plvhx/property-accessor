<?php

namespace Gandung\PropertyAccessor\Getter;

interface GetterInterface
{
    /**
     * Get multilayer array index or all object properties.
     *
     * @return array
     */
    public function get();
}
