<?php

namespace Xchert\Encapsulation\Test;

use PHPUnit\Framework\TestCase;
use Xchert\Encapsulation\MutableContainer;

class MutableContainerTest extends TestCase
{
    public function testMutableContainer(): void
    {
        $container = new MutableContainer([1, 2, 3, 4, 5]);
        $container->add(6);

        $this->assertSame(
            [1, 2, 3, 4, 5, 6],
            $container->toArray()
        );

        $container->clear();

        $this->assertSame([], $container->toArray());
    }

    public function testSplice(): void
    {
        $container = new MutableContainer([1, 2, 5, 6]);
        $container->splice(2, 0, [3, 4]);

        $this->assertSame([1, 2, 3, 4, 5, 6], $container->toArray());
    }

    public function testShift(): void
    {
        $container = new MutableContainer([1, 2, 3, 4, 5]);
        $one = $container->shift();

        $this->assertSame(1, $one);
        $this->assertSame([2, 3, 4, 5], $container->toArray());
    }

    public function testUnshift(): void
    {
        $container = new MutableContainer([1, 2, 3, 4, 5]);
        $container->unshift(-1, 0);

        $this->assertSame([-1, 0, 1, 2, 3, 4, 5], $container->toArray());
    }

    public function testPop(): void
    {
        $container = new MutableContainer([1, 2, 3, 4, 5]);
        $five = $container->pop();

        $this->assertSame(5, $five);
        $this->assertSame([1, 2, 3, 4], $container->toArray());
    }

}