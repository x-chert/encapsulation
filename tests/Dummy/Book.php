<?php

namespace Xchert\Encapsulation\Test\Dummy;

use Xchert\Encapsulation\EncapsulatedArray;

class Book extends EncapsulatedArray
{
    public function isFieldAllowed(string $field): bool
    {
        return \in_array($field, ['title', 'year', 'author']);
    }
}