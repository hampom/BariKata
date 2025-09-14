<?php

declare(strict_types=1);

namespace Hampom\BariKata\Plugins;

use Hampom\BariKata\TypedCollection;

interface CollectionPlugin
{
    public function getName(): string;

    public function apply(TypedCollection $collection, mixed ...$args): mixed;
}
