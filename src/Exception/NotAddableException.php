<?php

namespace Xchert\Encapsulation\Exception;

use Xchert\Encapsulation\Encapsulated;

class NotAddableException extends EncapsulationException
{
    public static function becauseOf(\Throwable $exception, string $field, Encapsulated $encapsulated): self
    {
        return new self(
            $encapsulated,
            \sprintf("Cannot add value to field '%s'.", $field),
            0,
            $exception
        );
    }

    public static function notArrayOrContainer(string $field, string $type, Encapsulated $encapsulated): self
    {
        return new self(
            $encapsulated,
            \sprintf("Value of field '%s' must be array or container to add values to it. %s given", $field, $type)
        );
    }
}
