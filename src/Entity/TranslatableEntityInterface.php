<?php

namespace EnderLab\TranslatableEntityBundle\Entity;

use EnderLab\TranslatableEntityBundle\Attributes\TranslatableField;
use EnderLab\TranslatableEntityBundle\Exception\TranslatableException;
use EnderLab\TranslatableEntityBundle\Services\CurrentLocaleService;
use Exception;
use JetBrains\PhpStorm\Pure;
use ReflectionClass;
use ReflectionProperty;

interface TranslatableEntityInterface
{
    public function getCurrentLocale(): string;
    public function getFallbackLocale(): string;
    public function __get(string $property);
    public function __set(string $property, mixed $value);
    public function __call(string $name, array $arguments);
}