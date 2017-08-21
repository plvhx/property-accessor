<?php

namespace Gandung\PropertyAccessor;

class Normalizer implements NormalizerInterface
{
    public function toCamelCase($data)
    {
        if (!is_string($data)) {
            throw new \InvalidArgumentException(
                sprintf("Parameter 1 of '%s' must be a string.", __METHOD__)
            );
        }

        if (strstr($data, '_')) {
            // if underscore sign has been detected in our string.
            // split it and cardinalized each element.
            $transformed = array_map(
                function ($q) {
                    return ucfirst(strtolower($q));
                },
                explode('_', $data)
            );
        }

        return isset($transformed) ? lcfirst(join($transformed)) : strtolower($data);
    }
}
