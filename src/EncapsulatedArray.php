<?php

namespace Xchert\Encapsulation;

use Xchert\Encapsulation\Exception\NotAllowedFieldException;

class EncapsulatedArray extends AbstractEncapsulated
{
	use ArrayTrait;

	/**
	 * @throws NotAllowedFieldException
	 */
	public function __construct(array $data = [])
	{
		foreach($data as $field => $value) {
			$this->_set($field, $value);
		}
	}
}
