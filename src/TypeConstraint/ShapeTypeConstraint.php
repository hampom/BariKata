<?php

declare(strict_types=1);

namespace Hampom\BariKata\TypeConstraint;

use CuyZ\Valinor\Mapper\MappingError;
use CuyZ\Valinor\Mapper\TreeMapper;
use CuyZ\Valinor\MapperBuilder;
use InvalidArgumentException;

final class ShapeTypeConstraint
{
    private string $definition;

    private static null|TreeMapper $mapper = null;

    public function __construct(string $definition)
    {
        if ($definition === '') {
            throw new InvalidArgumentException('Empty shape definition is now allowed.');
        }

        $this->definition = $definition;
    }

    private static function getMapper(): TreeMapper
    {
        if (self::$mapper === null) {
            self::$mapper = (new MapperBuilder())->mapper();
        }

        return self::$mapper;
    }

    public function validate(mixed $value): void
    {
        try {
            self::getMapper()->map($this->definition, $value);
            return;
        } catch (MappingError $e) {
            throw new InvalidArgumentException(sprintf(
                'Value does not match shape %s: %s',
                $this->definition,
                $e->getMessage(),
            ));
        } catch (\Throwable $e) {
            throw new InvalidArgumentException(
                "Failed to validate value against shape `{$this->definition}`: " . $e->getMessage(),
                0,
                $e,
            );
        }
    }
}
