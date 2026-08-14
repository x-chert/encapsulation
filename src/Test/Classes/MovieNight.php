<?php

namespace Xchert\Encapsulation\Test\Classes;

use Xchert\Encapsulation\EncapsulatedProperties;

class MovieNight extends EncapsulatedProperties
{
	protected string $movie;

	protected array $snacks = [];

	protected string $location;
}