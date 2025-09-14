<?php

declare(strict_types=1);

namespace Hampom\BariKata;

use Hampom\BariKata\Plugins\CollectionPlugin;
use Hampom\BariKata\Plugins\toArray;
use InvalidArgumentException;

abstract class TypedCollectionFactory
{
    /**
     * @var class-string T
     */
    protected static string $type;

    private array $defaultPlugins = [
        ['class' => toArray::class],
    ];

    final public function factory(array $items = []): TypedCollection
    {
        $pluginInstances = [];

        foreach ($this->defaultPlugins as $pluginConfig) {
            $pluginInstances[] = $this->instantiatePlugin($pluginConfig);
        }

        foreach (static::plugins() as $pluginConfig) {
            $pluginInstances[] = $this->instantiatePlugin($pluginConfig);
        }

        return new TypedCollection(static::$type, $items, $pluginInstances);
    }

    /**
     * @return array<array{class: class-string<CollectionPlugin>, args?: array}>
     */
    abstract protected static function plugins(): array;

    /**
     * @param array{class: class-string<CollectionPlugin>, args?: array} $pluginConfig
     */
    private function instantiatePlugin(array $pluginConfig): CollectionPlugin
    {
        $class = $pluginConfig['class'];
        $args = $pluginConfig['args'] ?? [];

        if (!class_exists($class)) {
            throw new InvalidArgumentException("Plugin class '$class' does not exist");
        }

        $plugin = new $class(...$args);

        if (!$plugin instanceof CollectionPlugin) {
            throw new InvalidArgumentException("Plugin '$class' must implement CollectionPlugin");
        }

        return $plugin;
    }
}
