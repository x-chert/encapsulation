<?php

namespace Xchert\Encapsulation;

use Xchert\Encapsulation\Exception\NotAllowedFieldException;

class ArrayEncapsulation extends AbstractEncapsulation
{
	use ArrayTrait;

	/**
	 * @throws NotAllowedFieldException
	 */
	public function set(string $field, mixed $value): void
	{
		$this->_set($field, $value);
	}

	public function unset(string $field): void
	{
		unset($this->data[$field]);
	}
}
