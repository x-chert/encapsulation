<?php

namespace Xchert\Encapsulation\Test;

use PHPUnit\Framework\TestCase;
use Xchert\Encapsulation\Exception\CannotUnsetException;
use Xchert\Encapsulation\Exception\PropertyNotExistsException;
use Xchert\Encapsulation\MutableContainer;
use Xchert\Encapsulation\Test\Classes\Blog;

class PropertyEncapsulationTest extends TestCase
{
    public function testPropertyEncapsulation(): void
    {
        $encapsulation = new Blog([
            'title' => 'Superfoods',
            'categories' => new MutableContainer(['Food']),
            'visitors' => ['Alice']
        ]);

        $this->assertTrue($encapsulation->has('categories'));
        $this->assertEquals(new MutableContainer(['Food']), $encapsulation->get('categories'));

        $this->assertFalse($encapsulation->has('publishDate'));

        // PropertyEncapsulation::has must return true even if property is not initialized
        $this->assertTrue($encapsulation->has('images'));
        // PropertyEncapsulation::get must return null if typed property is not initialized
        $this->assertNull($encapsulation->get('images'));

        $encapsulation->set('author', 'John Doe');
        $this->assertSame('John Doe', $encapsulation->get('author'));
        $encapsulation->unset('author');
        $this->assertNull($encapsulation->get('author'));
        $this->assertTrue($encapsulation->has('author'));

        $this->assertSame(['Alice'], $encapsulation->get('visitors'));
        $encapsulation->add('visitors', 'Bob');
        $this->assertSame(['Alice', 'Bob'], $encapsulation->get('visitors'));

        $this->assertNull($encapsulation->get('images'));
        $encapsulation->add('images', 'nuts.png');
        $this->assertSame(['nuts.png'], $encapsulation->get('images'));

        $encapsulation->add('categories', 'Health');
        $this->assertEquals(
            new MutableContainer(['Food', 'Health']),
            $encapsulation->get('categories')
        );

        $encapsulation->addList('visitors', ['Carol', 'Dave']);
        $this->assertSame(
            ['Alice', 'Bob', 'Carol', 'Dave'],
            $encapsulation->get('visitors')
        );

        $encapsulation->addList('categories', ['Fitness', 'Yoga']);
        $this->assertEquals(
            new MutableContainer(['Food', 'Health', 'Fitness', 'Yoga']),
            $encapsulation->get('categories')
        );

        $encapsulation->addList('keywords', ['Food', 'Health']);
        $this->assertSame(['Food', 'Health'], $encapsulation->get('keywords'));
    }

    public function testArrayAccess(): void
    {
        $encapsulation = new Blog(['author' => 'John Doe']);

        $this->assertTrue(isset($encapsulation['author']));
        $this->assertTrue($encapsulation['author'] === 'John Doe' && $encapsulation->get('author') === 'John Doe');

        // Must be true, uninitialized properties are considered as null
        $this->assertTrue(isset($encapsulation['title']));
        // Must be false since property does not exist
        $this->assertFalse(isset($encapsulation['subtitle']));

        $encapsulation['title'] = 'Superfoods';
        $this->assertTrue($encapsulation['title'] === 'Superfoods' && $encapsulation->get('title') === 'Superfoods');

        unset($encapsulation['author']);
        $this->assertTrue(isset($encapsulation['author']) && $encapsulation->has('author'));
        $this->assertNull($encapsulation->get('author'));

        $this->expectException(\RuntimeException::class);
        // Must throw exception since index cannot be empty
        $encapsulation[] = 'some Value';
    }

    public function testCannotUnset(): void
    {
        $encapsulation = new \Xchert\Encapsulation\Test\Classes\Blog([
            'title' => 'Superfoods',
            'author' => 'John Doe'
        ]);

        $encapsulation->unset('author');
        $this->expectException(CannotUnsetException::class);

        $encapsulation->unset('title');
    }

    public function testPropertyNotExistsConstructor(): void
    {
        $this->expectException(PropertyNotExistsException::class);
        new Blog(['subtitle' => 'Foods with powers']);
    }

    public function testPropertyNotExistsSet(): void
    {
        $encapsulation = new Blog();

        $this->expectException(PropertyNotExistsException::class);
        $encapsulation->set('subtitle', 'Foods with powers');
    }

    public function testPropertyNotExistsSetList(): void
    {
        $encapsulation = new Blog();

        $this->expectException(PropertyNotExistsException::class);
        $encapsulation->setList(['subtitle' => 'Foods with powers']);
    }

    public function testPropertyNotExistsAdd(): void
    {
        $encapsulation = new Blog();

        $this->expectException(PropertyNotExistsException::class);
        $encapsulation->add('tags', 'Food');
    }

    public function testPropertyNotExistsAddList(): void
    {
        $encapsulation = new Blog();
        $this->expectException(PropertyNotExistsException::class);

        $encapsulation->addList('tags', ['Food']);
    }

    public function testPropertyNotExistsArrayAccess(): void
    {
        $encapsulation = new Blog();

        $this->expectException(PropertyNotExistsException::class);
        $encapsulation['subtitle'] = 'Foods with powers';
    }

    public function testEmpty()
    {
        $encapsulation = new Blog();

        $this->assertTrue($encapsulation->isEmpty());

        $encapsulation->set('author', 'John Doe');
        $this->assertFalse($encapsulation->isEmpty());

        $encapsulation->unset('author');
        $this->assertTrue($encapsulation->isEmpty());
    }

    public function testIteration()
    {
        $encapsulation = new Blog(['title' => 'Superfoods', 'author' => 'John Doe']);
        $data = [];

        foreach($encapsulation as $field => $value) {
            $data[$field] = $value;
        }

        $this->assertSame($encapsulation->toArray(), $data);
    }

    public function testJsonSerialize(): void
    {
        $encapsulation = new Blog([
            'title' => 'Superfoods',
            'author' => 'John Doe',
            'images' => ['nuts.png', 'vegetables.png']
        ]);

        $json = \json_encode($encapsulation, \JSON_THROW_ON_ERROR);

        $this->assertJsonStringEqualsJsonString(
            '{"title":"Superfoods","author":"John Doe","images":["nuts.png","vegetables.png"],"visitors":[],"categories":null,"keywords":null}',
            $json
        );
    }

    public function testInitializeContainer(): void
    {
        $encapsulation = new Blog();
        $encapsulation->add('categories', 'Food Trends');

        $container = $encapsulation->get('categories');

        $this->assertInstanceOf(MutableContainer::class, $container);
    }
}