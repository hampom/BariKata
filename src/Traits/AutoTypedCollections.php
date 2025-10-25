<?php

declare(strict_types=1);

namespace Hampom\BariKata\Traits;

use Hampom\BariKata\Attributes\Typed;
use Hampom\BariKata\TypedCollection;

trait AutoTypedCollections
{
    public function __construct()
    {
        $ref = new \ReflectionClass($this);
        foreach ($ref->getProperties() as $prop) {
            $attr = $prop->getAttributes(Typed::class);
            if ($attr) {
                $typed = $attr[0]->newInstance();
                $prop->setAccessible(true);
                $prop->setValue($this, new TypedCollection($typed->definition));
            }
        }
    }
}
