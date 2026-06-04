<?php

namespace Xchert\Encapsulation\Exception;

use Xchert\Encapsulation\Encapsulated;

class EncapsulationException extends \Exception
{
    public function __construct(
        private readonly Encapsulated $encapsulation,
        string $message,
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getEncapsulation(): Encapsulated
    {
        return $this->encapsulation;
    }
}
