<?php

namespace Xchert\Encapsulation;

use Xchert\Encapsulation\Exception\PropertyNotExistsException;

abstract class EncapsulatedProperties extends AbstractEncapsulated
{
    use PropertyTrait;

	/**
	 * @throws PropertyNotExistsException
	 */
	public function __construct(array $data = [])
    {
		$this->_setList($data);
    }
}
