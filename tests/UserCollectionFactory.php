<?php

declare(strict_types=1);

namespace Test;

use Hampom\BariKata\TypedCollectionFactory;

final class UserCollectionFactory extends TypedCollectionFactory
{
    protected static string $type = User::class;

    protected static function plugins(): array
    {
        return [
            ['class' => CountStringPlugin::class],
        ];
    }
}
