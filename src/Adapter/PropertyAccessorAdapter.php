<?php

namespace Gandung\PropertyAccessor\Adapter;

use Gandung\PropertyAccessor\PropertyAccessorInterface;

interface PropertyAccessorAdapter extends PropertyAccessorInterface
{
    public function getAdapter();
}
