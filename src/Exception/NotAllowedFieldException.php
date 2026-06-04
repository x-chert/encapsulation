<?php

namespace Xchert\Encapsulation\Exception;

use Xchert\Encapsulation\Encapsulated;

class NotAllowedFieldException extends EncapsulationException
{
	public function __construct(private readonly string $field, Encapsulated $encapsulated)
	{
		parent::__construct(
			$encapsulated,
			\sprintf("Field '%s' is not allowed.", $field)
		);
	}

	public function getField(): string
	{
		return $this->field;
	}
}