<?php

declare(strict_types=1);

namespace Hampom\BariKata\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class Typed
{
    public function __construct(
        public string $definition,
    ) {}
}
