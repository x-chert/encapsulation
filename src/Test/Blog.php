<?php

namespace Xchert\Encapsulation\Test;

use Xchert\Encapsulation\MutableContainer;
use Xchert\Encapsulation\PropertyEncapsulation;

class Blog extends PropertyEncapsulation
{
	protected string $title;

	protected ?string $author = null;

	protected array $images;

	protected array $visitors = [];

	protected MutableContainer $categories;

	protected array $keywords;
}