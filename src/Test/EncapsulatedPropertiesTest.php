<?php

namespace Xchert\Encapsulation\Test;

use PHPUnit\Framework\TestCase;
use Xchert\Encapsulation\Exception\PropertyNotExistsException;

class EncapsulatedPropertiesTest extends TestCase
{
    public function testEncapsulatedProperties(): void
    {
        $encapsulation = new MovieNight([
            'movie' => 'The social network',
            'snacks' => ['nuts']
        ]);

        $this->assertTrue($encapsulation->has('movie'));
        $this->assertSame('The social network', $encapsulation->get('movie'));

        $this->assertFalse($encapsulation->has('time'));

        // EncapsulatedProperties::has must return true if property exists even if it's not set
        $this->assertTrue($encapsulation->has('location'));

        $this->assertSame(
            [
                'movie' => 'The social network',
                'snacks' => ['nuts'],
                'location' => null
            ],
            $encapsulation->toArray()
        );

        $this->assertSame(
            ['movie', 'snacks', 'location'],
            $encapsulation->getFields()
        );

        $this->assertSame(
            [
                'movie' => 'The social network',
                'snacks' => ['nuts']
            ],
            $encapsulation->getList(['movie', 'snacks'])
        );
    }

    public function testPropertyNotExists(): void
    {
        $this->expectException(PropertyNotExistsException::class);

        new MovieNight([
            'time' => 'today'
        ]);
    }

    public function testIteration(): void
    {
        $encapsulation = new MovieNight([
            'movie' => 'The social network',
            'snacks' => ['nuts'],
            'location' => 'at home'
        ]);

        $result = [];

        foreach($encapsulation as $key => $value) {
            $result[$key] = $value;
        }

        $this->assertSame($encapsulation->toArray(), $result);
    }

    public function testJsonSerialize(): void
    {
        $encapsulation = new MovieNight([
            'movie' => 'The social network',
            'snacks' => ['nuts']
        ]);

        $json = \json_encode($encapsulation, \JSON_THROW_ON_ERROR);

        $this->assertJsonStringEqualsJsonString(
            '{"movie":"The social network","snacks":["nuts"],"location":null}',
            $json
        );
    }

    public function testPrivateProperties(): void
    {
        $party = new PrivateParty([
            'location' => 'at home'
        ]);

        $this->assertTrue($party->has('location'));
        $this->assertTrue($party->has('time'));

        $this->assertSame('at home', $party->get('location'));
        $this->assertSame(null, $party->get('time'));

        $barbequeParty = new PrivateBarbequeParty([
            'location' => 'at home',
            'food' => 'beef'
        ]);

        $this->assertTrue($barbequeParty->has('location'));
        $this->assertTrue($barbequeParty->has('time'));
        $this->assertTrue($barbequeParty->has('food'));
        $this->assertTrue($barbequeParty->has('drinks'));

        $this->assertSame('at home', $barbequeParty->get('location'));
        $this->assertSame(null, $barbequeParty->get('time'));
        $this->assertSame('beef', $barbequeParty->get('food'));
        $this->assertSame(null, $barbequeParty->get('drinks'));
    }
}