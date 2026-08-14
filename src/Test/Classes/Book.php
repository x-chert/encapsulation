<?php

namespace Xchert\Encapsulation\Test\Classes;

use Xchert\Encapsulation\EncapsulatedArray;

class Book extends EncapsulatedArray
{
    public function isFieldAllowed(string $field): bool
    {
        return \in_array($field, ['title', 'year', 'author']);
    }
}