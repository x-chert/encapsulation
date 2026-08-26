<?php

namespace Xchert\Encapsulation;

abstract class EncapsulatedProperties extends AbstractEncapsulated
{
    use PropertyTrait;

    public function __construct(array $data = [])
    {
        $this->_setList($data);
    }
}
