<?php

declare(strict_types=1);

namespace Test;

use PHPUnit\Framework\TestCase;

final class FactoryTest extends TestCase
{
    public function testFactoryRegistersDefault(): void
    {
        $users = (new UserCollectionFactory())->factory([
            new User(1, 'taro'),
        ]);

        $this->assertSame('taro', $users->toArray()[0]->name);
    }

    public function testFactoryRegisterPlugin(): void
    {
        $users = (new UserCollectionFactory())->factory([
            new User(1, 'taro'),
        ]);

        $this->assertSame('1', $users->countString());
    }
}
