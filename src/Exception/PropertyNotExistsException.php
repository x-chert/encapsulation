<?php

namespace Xchert\Encapsulation\Exception;

use Xchert\Encapsulation\Encapsulated;

class PropertyNotExistsException extends EncapsulationException
{
    public function __construct(
        Encapsulated $encapsulation,
        private readonly string $property
    ) {
        parent::__construct(
            $encapsulation,
            \sprintf("Property '%s' does not exist in %s", $property, \get_class($encapsulation))
        );
    }

    public function getProperty(): string
    {
        return $this->property;
    }
}
