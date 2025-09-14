<?php

declare(strict_types=1);

namespace Test;

use Hampom\BariKata\Plugins\CollectionPlugin;
use Hampom\BariKata\TypedCollection;

final class CountStringPlugin implements CollectionPlugin
{
    public function getName(): string
    {
        return 'countString';
    }

    public function apply(TypedCollection $collection, ...$args): string
    {
        return (string) $collection->count();
    }
}
