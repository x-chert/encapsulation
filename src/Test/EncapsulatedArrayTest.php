<?php

namespace Xchert\Encapsulation\Test;

use PHPUnit\Framework\TestCase;
use Xchert\Encapsulation\EncapsulatedArray;
use Xchert\Encapsulation\Exception\NotAllowedFieldException;

class EncapsulatedArrayTest extends TestCase
{
	public function testEncapsulatedArray(): void
	{
		$encapsulation = new EncapsulatedArray([
			'title' => 'The lord of the rings',
			'author' => 'J.R.R Tolkien',
			'year' => 1954
		]);

		$this->assertTrue($encapsulation->has('title'));
		$this->assertSame('The lord of the rings', $encapsulation->get('title'));

		$this->assertFalse($encapsulation->has('rating'));

		$this->assertSame(
			[
				'title' => 'The lord of the rings',
				'author' => 'J.R.R Tolkien',
				'year' => 1954
			],
			$encapsulation->toArray()
		);

		$this->assertSame(
			['title', 'author', 'year'],
			$encapsulation->getFields()
		);

		$this->assertSame(
			[
				'title' => 'The lord of the rings',
				'author' => 'J.R.R Tolkien'
			],
			$encapsulation->getList(['title', 'author'])
		);
	}

	public function testAllowedFields(): void
	{
		$this->expectException(NotAllowedFieldException::class);

		// Must throw NotAllowedFieldException since field 'isbn' is not allowed in class Book
		$book = new Book([
			'title' => 'The lord of the rings',
			'isbn' => '<some isbn>'
		]);
	}

	public function testIteration(): void
	{
		$book = new EncapsulatedArray([
			'title' => 'The lord of the rings',
			'author' => 'J.R.R Tolkien',
			'year' => 1954
		]);

		$data = [];

		foreach($book as $field => $value) {
			$data[$field] = $value;
		}

		$this->assertSame($book->toArray(), $data);
	}

	public function testJsonSerialize(): void
	{
		$book = new EncapsulatedArray([
			'title' => 'The lord of the rings',
			'author' => 'J.R.R Tolkien',
		]);

		$json = \json_encode($book, \JSON_THROW_ON_ERROR);

		$this->assertJsonStringEqualsJsonString(
			'{"title":"The lord of the rings","author":"J.R.R Tolkien"}',
			$json
		);
	}

}