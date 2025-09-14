<?php

declare(strict_types=1);

namespace Test;

use Hampom\BariKata\TypedCollection;
use PHPUnit\Framework\TestCase;

final class PluginTest extends TestCase
{
    public function testMapPluginWorks(): void
    {
        $c = new TypedCollection(User::class, [
            new User(1, 'Taro'),
            new User(2, 'Jiro'),
        ]);

        $c->registerPlugin(new CountStringPlugin());

        $count = $c->countString();

        $this->assertSame('2', $count);
    }
}
