<?php

namespace Xchert\Encapsulation\Test;

use PHPUnit\Framework\TestCase;
use Xchert\Encapsulation\Container;

class ContainerTest extends TestCase
{
    public function testContainer(): void
    {
        $container = new Container(['foo', 'bar']);

        $this->assertSame(['foo', 'bar'], $container->toArray());
        $this->assertSame('bar', $container->getAt(1));
        $this->assertSame(2, count($container));
        $this->assertFalse($container->isEmpty());
    }

    public function testMap(): void
    {
        $container = new Container(['foo', 'bar']);

        $new = $container->map(function (string $value): string {
            return \strtoupper($value);
        });

        $this->assertEquals(
            new Container(['FOO', 'BAR']),
            $new
        );
    }

    public function testReduce(): void
    {
        $container = new Container([3, 4, 4]);

        $sum = $container->reduce(function ($sum, $item) {
            return $sum + $item;
        }, 0);

        $this->assertSame(11, $sum);
    }

    public function testFilter(): void
    {
        $container = new Container(['', 2, 8]);

        $filtered = $container->filter();
        $this->assertEquals(new Container([2, 8]), $filtered);

        $lessThan5 = $container->filter(function ($item): bool {
            return \is_int($item) && $item < 5;
        });
        $this->assertEquals(new Container([2]), $lessThan5);
    }

    public function testSlice(): void
    {
        $container = new Container([1, 2, 3, 4]);

        $slice = $container->slice(1, 2);
        $this->assertEquals(
            new Container([2, 3]),
            $slice
        );
    }

    public function testUnique(): void
    {
        $container = new Container([1, 1, 2, 3, 3, 4, 5, 5]);
        $unique = $container->unique();

        $this->assertEquals(
            new Container([1, 2, 3, 4, 5]),
            $unique
        );
    }

    public function testReverse(): void
    {
        $container = new Container([1, 2, 3, 4, 5]);
        $reverse = $container->reverse();

        $this->assertEquals(
            new Container([5, 4, 3, 2, 1,]),
            $reverse
        );
    }

    public function testSearch(): void
    {
        $container = new Container([1, 2, 3, 4, 5]);
        $index = $container->search(3, true);

        $this->assertEquals(2, $index);
    }

    public function testHas(): void
    {
        $container = new Container([1, 2, 3, 4, 5]);
        $this->assertTrue($container->has(3));
    }

    public function testSort(): void
    {
        $container = new Container([4, 2, 1, 5, 3]);
        $container->sort();

        $this->assertEquals(
            new Container([1, 2, 3, 4, 5]),
            $container
        );
    }

    public function testChunk(): void
    {
        $container = new Container([1, 2, 3, 4, 5, 6]);
        $chunks = $container->chunk(2);

        $this->assertEquals(
            [new Container([1, 2]), new Container([3, 4]), new Container([5, 6])],
            $chunks
        );
    }

    public function testIteration(): void
    {
        $container = new Container([1, 2, 3, 4, 5]);
        $data = [];

        foreach($container as $item) {
            $data[] = $item;
        }

        $this->assertSame($data, $container->toArray());
    }

    public function testJsonSerialize(): void
    {
        $container = new Container([1, 2, 3, 4, 5]);
        $json = \json_encode($container);

        $this->assertJsonStringEqualsJsonString(
            '[1,2,3,4,5]',
            $json
        );
    }

}