<?php

declare(strict_types=1);

namespace Hampom\BariKata;

use ArrayAccess;
use BadMethodCallException;
use Countable;
use Hampom\BariKata\Plugins\CollectionPlugin;
use InvalidArgumentException;
use IteratorAggregate;
use Traversable;

/**
 * @template T of object
 * @implements ArrayAccess<int, T>
 * @implements  IteratorAggregate<int, T>
 */
final class TypedCollection implements ArrayAccess, IteratorAggregate, Countable
{
    /**
     * @var T[]
     */
    private array $items = [];

    /**
     * @var array<string, CollectionPlugin>
     */
    private array $plugins = [];

    /**
     * @param class-string<T> $type
     * @param T[] $items
     * @param CollectionPlugin[] $plugins
     */
    public function __construct(
        public readonly string $type,
        array $items = [],
        array $plugins = [],
    ) {
        foreach ($items as $item) {
            $this->offsetSet(null, $item);
        }

        foreach ($plugins as $plugin) {
            $this->registerPlugin($plugin);
        }
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->items[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->items[$offset] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (!$this->isOffType($value)) {
            $type = $this->type;
            $given = get_debug_type($value);

            if (is_array($value)) {
                throw new InvalidArgumentException(
                    "Arrays are not allowed in BariKata<$type>. Use TypeCollection instead.",
                );
            }

            throw new InvalidArgumentException("Value must be of type $type, $given given.");
        }

        if ($offset === null) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->items[$offset]);
    }

    public function getIterator(): Traversable
    {
        yield from $this->items;
    }

    public function count(): int
    {
        return count($this->items);
    }

    private function isOffType(mixed $value): bool
    {
        if ($this->type === 'mixed') {
            return !is_array($value);
        }

        if (class_exists($this->type) || interface_exists($this->type)) {
            return $value instanceof $this->type;
        }

        if (is_scalar($value)) {
            return get_debug_type($value) === $this->type;
        }

        return false;
    }

    public function registerPlugin(CollectionPlugin $plugin): void
    {
        $this->plugins[$plugin->getName()] = $plugin;
    }

    public function getPlugins(): array
    {
        return $this->plugins;
    }

    public function __call(string $name, array $arguments)
    {
        if (!isset($this->plugins[$name])) {
            throw new BadMethodCallException("Plugin $name is not registered");
        }

        return $this->plugins[$name]->apply($this, ...$arguments);
    }
}
