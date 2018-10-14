<?php

namespace Pasttaga\GoogleTranslateBundle\Translate;

/**
 * Class MethodManager.
 *
 * This is the class that manage available methods
 */
class MethodManager
{
    /**
     * @var array Methods available
     */
    protected $methods = [];

    /**
     * Constructor.
     *
     * @param array $methods
     */
    public function __construct(iterable $methods)
    {
        foreach ($methods as $method) {
            if ($method instanceof MethodInterface) {
                $this->methods[$method->getName()] = $method;
            }
        }
    }

    /**
     * Returns all methods available.
     *
     * @return array
     */
    public function all()
    {
        return $this->methods;
    }
}
