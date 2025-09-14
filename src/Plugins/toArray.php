<?php

declare(strict_types=1);

namespace Hampom\BariKata\Plugins;

use Hampom\BariKata\TypedCollection;

/**
 * @template T
 * @implements CollectionPlugin<T>
 */
final class toArray implements CollectionPlugin
{
    public function getName(): string
    {
        return 'toArray';
    }

    /**
     * @param TypedCollection<T> $collection
     * @return list<T>
     */
    public function apply(TypedCollection $collection, ...$args): mixed
    {
        return iterator_to_array($collection, false);
    }
}
