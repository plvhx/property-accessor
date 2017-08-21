<?php

namespace Gandung\PropertyAccessor;

interface PropertyAccessorInterface
{
    public function getValue($handler, $keyOrPropertyPath, $force = false);

    public function setValue(&$handler, $keyOrPropertyPath, $val, $force = false);
}
