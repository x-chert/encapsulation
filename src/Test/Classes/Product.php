<?php

namespace Xchert\Encapsulation\Test\Classes;

use Xchert\Encapsulation\ArrayEncapsulation;

class Product extends ArrayEncapsulation
{
    public function isFieldAllowed(string $field): bool
    {
        return \in_array($field, ['name', 'price', 'productNumber']);
    }
}