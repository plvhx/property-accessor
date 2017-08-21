<?php

namespace Gandung\PropertyAccessor;

use Gandung\PropertyAccessor\Getter\ArrayKey;
use Gandung\PropertyAccessor\Getter\ObjectProperty;
use Gandung\PropertyAccessor\Exception\UndefinedIndexOrOffsetException;

class PropertyAccessor implements PropertyAccessorInterface
{
    /**
     * @var ArrayKey
     */
    private $array;

    /**
     * @var ObjectProperty
     */
    private $object;

    /**
     * @var Normalizer
     */
    private $normalizer;

    /**
     * @var boolean
     */
    private $magicMethod;

    public function __construct(
        NormalizerInterface $normalizer,
        ArrayKey $array,
        ObjectProperty $object
    ) {
        $this->normalizer = $normalizer;
        $this->array = $array;
        $this->object = $object;
    }

    private function useMagicMethod($use = false)
    {
        $this->magicMethod = $use;
    }

    /**
     * Enable capability to get or set non-public properties
     * using __set or __get magic method.
     *
     * @return PropertyAccessor
     */
    public function enableMagicMethod()
    {
        $this->useMagicMethod(true);

        return $this;
    }

    /**
     * Disable capability to get or set non-public properties
     * using __set or __get magic method.
     *
     * @return PropertyAccessor
     */
    public function disableMagicMethod()
    {
        $this->useMagicMethod(false);

        return $this;
    }

    public function getValue($handler, $keyOrPropertyPath, $force = false)
    {
        $endpoint = ['context' => null, 'value' => null];

        if (is_array($handler)) {
            $this->array->parse($keyOrPropertyPath);
            $keys = $this->array->get();

            foreach ($keys as $key) {
                if (is_null($endpoint['context'])) {
                    if (!isset($handler[$key])) {
                        throw new UndefinedIndexOrOffsetException(
                            sprintf("Undefined index '%s'.", $key)
                        );

                        break;
                    }

                    $endpoint['context'] = $handler[$key];
                    $endpoint['value'] = $endpoint['context'];
                } else {
                    if (!isset($endpoint['context'][$key])) {
                        throw new UndefinedIndexOrOffsetException(
                            sprintf("Undefined index '%s'.", $key)
                        );

                        break;
                    } else {
                        $endpoint['context'] = $endpoint['context'][$key];
                        $endpoint['value'] = $endpoint['context'];
                    }
                }
            }
        } elseif (is_object($handler)) {
            $this->object->setObject($handler);
            $this->object->setProperty($keyOrPropertyPath);
            $key = $this->object->get();
            $endpoint['context'] = $handler;
            $refl = new \ReflectionClass($endpoint['context']);

            if (!$force) {
                if (!is_null($key)) {
                    $key = $this->normalizer->toCamelCase($key);
                    $q = $refl->getProperty($key);

                    if ($q->isPublic()) {
                        $endpoint['value'] = $endpoint['context']->{$key};
                    } else {
                        if ($this->magicMethod) {
                            if (!$this->hasMagicGetter($endpoint['context'])) {
                                throw new \LogicException(
                                    sprintf(
                                        "Unable to get class property via __get if '%s' does not have " .
                                        " or implement __get method.", get_class($endpoint['context'])
                                    )
                                );
                            } else {
                                $endpoint['value'] = $endpoint['context']->{$key};
                            }
                        } else {
                            if (!$this->hasGetter($endpoint['context'], 'get' . ucfirst($key))) {
                                throw new \LogicException(
                                    sprintf(
                                        "'%s' doesn't have getter method for class property '%s' nor " .
                                        " implementing  __get magic method.",
                                        get_class($endpoint['context']),
                                        $key
                                    )
                                );
                            } else {
                                $endpoint['value'] = $refl->getMethod('get' . ucfirst($key))
                                    ->invoke($endpoint['context']);
                            }
                        }
                    }
                }
            } else {
                if (!is_null($key)) {
                    $key = $this->normalizer->toCamelCase($key);
                    $q = $refl->getProperty($key);

                    // If the current property has protected or private access,
                    // release the restriction lock.
                    if ($q->isProtected() || $q->isPrivate()) {
                        $q->setAccessible(true);
                    }

                    $endpoint['value'] = $q->getValue($endpoint['context']);

                    // Several I/O event has been performed.
                    // So, activate the restriction lock.
                    $q->setAccessible(false);
                }
            }
        }

        return is_null($endpoint['context'])
            ? null
            : $endpoint['value'];
    }

    public function setValue(&$handler, $keyOrPropertyPath, $val, $force = false)
    {
        $endpoint = ['context' => null];

        if (is_array($handler)) {
            $this->array->parse($keyOrPropertyPath);
            $keys = $this->array->get();

            foreach ($keys as $key) {
                if (is_null($endpoint['context'])) {
                    if (!isset($handler[$key])) {
                        throw new UndefinedIndexOrOffsetException(
                            sprintf("Undefined index '%s'.", $key)
                        );

                        break;
                    }

                    $endpoint['context'] = &$handler[$key];
                } else {
                    if (!isset($endpoint['context'][$key])) {
                        throw new UndefinedIndexOrOffsetException(
                            sprintf("Undefined index '%s'.", $key)
                        );

                        break;
                    } else {
                        $endpoint['context'] = &$endpoint['context'][$key];
                    }
                }
            }

            $endpoint['context'] = $val;
        } elseif (is_object($handler)) {
            $this->object->setObject($handler);
            $this->object->setProperty($keyOrPropertyPath);
            $key = $this->object->get();
            $endpoint['context'] = &$handler;
            $refl = new \ReflectionClass($endpoint['context']);

            if (!$force) {
                if (!is_null($key)) {
                    $key = $this->normalizer->toCamelCase($key);
                    $q = $refl->getProperty($key);

                    if ($q->isPublic()) {
                        $endpoint['context']->{$key} = $val;
                    } else {
                        if ($this->magicMethod) {
                            if (!$this->hasMagicSetter($endpoint['context'])) {
                                throw new \LogicException(
                                    sprintf(
                                        "Unable to set class property via __set if '%s' doesn't have " .
                                        "or implement __set method.", get_class($endpoint['context'])
                                    )
                                );
                            } else {
                                $endpoint['context']->{$key} = $val;
                            }
                        } else {
                            if (!$this->hasSetter($endpoint['context'], 'set' . ucfirst($key))) {
                                throw new \LogicException(
                                    sprintf(
                                        "'%s' doesn't have setter method for class property '%s' nor " .
                                        " implementing  __set magic method.",
                                        get_class($endpoint['context']),
                                        $key
                                    )
                                );
                            } else {
                                $refl->getMethod('set' . ucfirst($key))
                                    ->invoke($endpoint['context'], $val);
                            }
                        }
                    }
                }
            } else {
                if (!is_null($key)) {
                    $key = $this->normalizer->toCamelCase($key);
                    $q = $refl->getProperty($key);

                    // If the current property has protected or private access,
                    // release the restriction lock.
                    if ($q->isProtected() || $q->isPrivate()) {
                        $q->setAccessible(true);
                    }

                    $q->setValue($endpoint['context'], $val);

                    // Several I/O event has been performed.
                    // So, activate the restriction lock.
                    $q->setAccessible(false);
                }
            }
        }
    }

    private function checkMethod($handler, $method)
    {
        $refl = new \ReflectionClass($handler);

        return $refl->hasMethod($method);
    }

    private function hasMagicSetter($handler)
    {
        return $this->checkMethod($handler, '__set');
    }

    private function hasSetter($handler, $setterMethod)
    {
        return $this->checkMethod($handler, $setterMethod);
    }

    private function hasMagicGetter($handler)
    {
        return $this->checkMethod($handler, '__get');
    }

    private function hasGetter($handler, $getterMethod)
    {
        return $this->checkMethod($handler, $getterMethod);
    }
}
