<?php

declare(strict_types=1);

namespace Test;

use Hampom\BariKata\TypedCollection;
use PHPUnit\Framework\TestCase;

final class TypedCollectionTest extends TestCase
{
    public function testAcceptsCorrectType(): void
    {
        $c = new TypedCollection(User::class);
        $c[] = new User(1, 'taro');

        $this->assertCount(1, $c);
        $this->assertInstanceOf(User::class, $c[0]);
    }

    public function testRejectsWrongType(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $c = new TypedCollection(User::class);
        $c[] = 'string-not-allowed';
    }
}
