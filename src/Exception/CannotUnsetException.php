<?php

namespace Xchert\Encapsulation\Exception;

use Xchert\Encapsulation\Encapsulated;

class CannotUnsetException extends EncapsulationException
{
	public function __construct(
		Encapsulated $encapsulation,
		private readonly string $field
	) {
		parent::__construct(
			$encapsulation,
			\sprintf("Cannot unset field '%s'", $field)
		);
	}

	public function getField(): string
	{
		return $this->field;
	}
}
