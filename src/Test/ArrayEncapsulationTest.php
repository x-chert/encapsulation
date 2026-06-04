<?php

namespace Xchert\Encapsulation\Test;

use PHPUnit\Framework\TestCase;
use Xchert\Encapsulation\ArrayEncapsulation;
use Xchert\Encapsulation\Exception\NotAllowedFieldException;

class ArrayEncapsulationTest extends TestCase
{
	public function testArrayEncapsulation(): void
	{
		$encapsulation = new ArrayEncapsulation([
			'movie' => 'The social network',
			'snacks' => ['nuts']
		]);

		$this->assertTrue($encapsulation->has('snacks'));
		$this->assertSame(['nuts'], $encapsulation->get('snacks'));

		$encapsulation->set('time', 'today');
		$this->assertTrue($encapsulation->has('time'));
		$this->assertSame('today', $encapsulation->get('time'));

		$this->assertFalse($encapsulation->has('location'));

		// ArrayEncapsulation::has must return false when field was unset
		$encapsulation->unset('time');
		$this->assertFalse($encapsulation->has('time'));

		$encapsulation->add('snacks', 'chocolate');
		$this->assertSame(['nuts', 'chocolate'], $encapsulation->get('snacks'));

		$encapsulation->add('drinks', 'cola');
		$this->assertSame(['cola'], $encapsulation->get('drinks'));

		$encapsulation->addList('snacks', ['chips', 'fries']);
		$this->assertSame(['nuts', 'chocolate', 'chips', 'fries'], $encapsulation->get('snacks'));

		$encapsulation->addList('friends', ['Alice', 'Bob']);
		$this->assertSame(['Alice', 'Bob'], $encapsulation->get('friends'));

		$this->assertSame('The social network', $encapsulation->get('movie'));
		$this->assertSame(null, $encapsulation->get('location'));
		$encapsulation->setList([
			'movie' => 'The big short',
			'location' => 'at home'
		]);
		$this->assertSame('The big short', $encapsulation->get('movie'));
		$this->assertSame('at home', $encapsulation->get('location'));

		$this->assertSame(
			[
				'movie' => 'The big short',
				'snacks' => ['nuts', 'chocolate', 'chips', 'fries'],
				'drinks' => ['cola'],
				'friends' => ['Alice', 'Bob'],
				'location' => 'at home'
			],
			$encapsulation->toArray()
		);

		$this->assertSame(
			['movie', 'snacks', 'drinks', 'friends', 'location'],
			$encapsulation->getFields()
		);

		$this->assertSame(
			[
				'movie' => 'The big short',
				'location' => 'at home'
			],
			$encapsulation->getList(['movie', 'location'])
		);
	}

	public function testArrayAccess()
	{
		$encapsulation = new ArrayEncapsulation(['pi' => 3.14159]);

		$this->assertTrue(isset($encapsulation['pi']));
		$this->assertTrue($encapsulation['pi'] === 3.14159 && $encapsulation->get('pi') === 3.14159);

		$this->assertFalse(isset($encapsulation['eulerian number']));

		$encapsulation['eulerian number'] = 2.71828;
		$this->assertTrue(isset($encapsulation['eulerian number']) && $encapsulation->has('eulerian number'));
		$this->assertTrue($encapsulation['eulerian number'] === 2.71828 && $encapsulation->get('eulerian number') === 2.71828);

		unset($encapsulation['pi']);
		$this->assertFalse(isset($encapsulation['pi']) && $encapsulation->has('pi'));

		$this->expectException(\RuntimeException::class);
		// Must throw exception since index cannot be empty
		$encapsulation[] = 'some Value';
	}

	public function testAllowedFieldsConstructor(): void
	{
		$this->expectException(NotAllowedFieldException::class);

		new Product(['description' => 'My fancy product']);
	}

	public function testAllowedFieldsSet(): void
	{
		$product = new Product();
		$this->expectException(NotAllowedFieldException::class);

		$product->set('description', 'My fancy product');
	}

	public function testAllowedFieldsSetList(): void
	{
		$product = new Product();
		$this->expectException(NotAllowedFieldException::class);

		$product->setList([
			'description' => 'My fancy product',
		]);
	}

	public function testAllowedFieldsAdd(): void
	{
		$product = new Product();
		$this->expectException(NotAllowedFieldException::class);

		$product->add('images', 'my_product.png');
	}

	public function testAllowedFieldsAddList(): void
	{
		$product = new Product();
		$this->expectException(NotAllowedFieldException::class);

		$product->addList('images', ['my_product.png']);
	}

	public function testNotAllowedFieldsArrayAccess(): void
	{
		$product = new Product();
		$this->expectException(NotAllowedFieldException::class);

		$product['description'] = 'My fancy product';
	}

	public function testEmpty()
	{
		$encapsulation = new ArrayEncapsulation();

		$this->assertTrue($encapsulation->isEmpty());

		$encapsulation->set('foo', 'foo');
		$this->assertFalse($encapsulation->isEmpty());

		$encapsulation->unset('foo');
		$this->assertTrue($encapsulation->isEmpty());
	}

	public function testIteration()
	{
		$encapsulation = new ArrayEncapsulation(['foo' => 'foo', 'bar' => 'bar']);
		$data = [];

		foreach($encapsulation as $field => $value) {
			$data[$field] = $value;
		}

		$this->assertSame($encapsulation->toArray(), $data);
	}

	public function testJsonSerialize(): void
	{
		$book = new ArrayEncapsulation([
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