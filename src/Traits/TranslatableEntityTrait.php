<?php

namespace EnderLab\TranslatableEntityBundle\Traits;

use EnderLab\TranslatableEntityBundle\Attributes\TranslatableField;
use EnderLab\TranslatableEntityBundle\Exception\TranslatableException;
use EnderLab\TranslatableEntityBundle\Services\CurrentLocaleService;
use Exception;
use JetBrains\PhpStorm\Pure;
use ReflectionClass;
use ReflectionProperty;

trait TranslatableEntityTrait
{
    protected static string $DEFAULT_VALUE = '';
    protected array $attributes = [];
    protected bool $initialized = false;

    #[Pure]
    public function getCurrentLocale(): string
    {
        return CurrentLocaleService::getCurrentLocale();
    }

    #[Pure]
    public function getFallbackLocale(): string
    {
        return CurrentLocaleService::getFallbackLocale();
    }

    public function __get(string $property)
    {
        $this->initAttributes();
        $locale = $this->getCurrentLocale();

        $regex  = '/^('.(implode('|', array_keys($this->attributes))).'|'.(implode('|', array_map('ucfirst', array_keys($this->attributes)))).')';
        $regex .= '(\w*)?$/';
        //'([A-Z][a-z])?$/';
        preg_match_all($regex, $property, $matches);

        if (
            isset($matches[1][0]) &&
            trim($matches[1][0]) !== '' &&
            false !== $this->attributeExists(lcfirst($matches[1][0]))
        ) {
            $property = lcfirst($matches[1][0]);
        }

        if (isset($matches[2][0]) && trim($matches[2][0]) !== '') {
            $locale = strtolower(trim($matches[2][0]));
        }

        if (false === $this->attributeExists($property)) {
            throw new Exception('Field "'.$property.'" does not exist in '.get_called_class());
        }

        return $this->getValue($locale, $property);
    }

    public function __set(string $property, mixed $value)
    {
        $this->initAttributes();
        $locale = $this->getCurrentLocale();

        $regex  = '/^('.(implode('|', array_keys($this->attributes))).'|'.(implode('|', array_map('ucfirst', array_keys($this->attributes)))).')';
        $regex .= '(\w*)?$/';
        //'([A-Z][a-z])?$/';
        preg_match_all($regex, $property, $matches);

        if (
            isset($matches[1][0]) &&
            trim($matches[1][0]) !== '' &&
            false !== $this->attributeExists(lcfirst($matches[1][0]))
        ) {
            $property = lcfirst($matches[1][0]);
        }

        if (isset($matches[2][0]) && trim($matches[2][0]) !== '') {
            $locale = strtolower(trim($matches[2][0]));
        }

        if (false === $this->attributeExists($property)) {
            throw new TranslatableException('Field "'.$property.'" does not exist in '.get_called_class());
        }

        return $this->setValue($locale, $property, $value);
    }

    /**
     * get[Field] - return field with current locale
     * [field] - return field with current locale
     * [field]() - return field with current locale
     * get[Field]Fr - return field with "fr" locale
     * get[Field]En - return field with "en" locale
     * set[Field](value) _ set value to field with current locale
     * [field]($value) - set value to field with current locale
     * set[Field]Fr(value) - set value to field with "fr" locale
     * set[Field]En(value) - set value to field with "en" locale
     */
    public function __call(string $name, array $arguments)
    {
        $this->initAttributes();
        $prefix = null;
        $property = null;
        $locale = $this->getCurrentLocale();

        $regex  = '/^(get|set)?';
        $regex .= '('.(implode('|', array_keys($this->attributes))).'|'.(implode('|', array_map('ucfirst', array_keys($this->attributes)))).')';
        $regex .= '(\w*)?$/';
        //'([A-Z][a-z])?$/';
        preg_match_all($regex, $name, $matches);

        if (
            isset($matches[1][0]) &&
            trim($matches[1][0]) !== '' &&
            in_array(trim($matches[1][0]), ['get', 'set'])
        ) {
            $prefix = strtolower(trim($matches[1][0]));
        } else {
            $prefix = count($arguments) > 0 ? 'set' : 'get';
        }

        if (
            isset($matches[2][0]) &&
            trim($matches[2][0]) !== '' &&
            false !== $this->attributeExists(lcfirst($matches[2][0]))
        ) {
            $property = lcfirst($matches[2][0]);
        }

        if (
            isset($matches[3][0]) &&
            trim($matches[3][0]) !== ''
        ) {
            $locale = strtolower(trim($matches[3][0]));
        } else {
            if ($prefix === 'set' && count($arguments) > 1) {
                $locale = array_shift($arguments);
            }

            if ($prefix === 'get' && count($arguments) > 0) {
                $locale = array_shift($arguments);
            }
        }

        return match ($prefix) {
            'get' => $this->getValue($locale, $property),
            'set' => $this->setValue($locale, $property, $arguments[0]),
            default => null,
        };
    }

    private function attributeExists($name): bool
    {
        if (array_key_exists($name, $this->attributes)) {
            return true;
        }

        return false;
    }

    private function getValue($locale, $property): string|array
    {
        /** @var ReflectionProperty $reflectionProperty */
        $reflectionProperty = $this->attributes[$property];
        $reflectionProperty->setAccessible(true);

        $data = $reflectionProperty->getValue($this);

        if ($locale === 'all') {
            return $data;
        }

        if (!isset($data[$locale])) {
            if ($_ENV['APP_ENV'] === 'dev') {
                return self::$DEFAULT_VALUE;
            } else {
                return !isset($data[$this->getFallbackLocale()]) ? self::$DEFAULT_VALUE : $data[$this->getFallbackLocale()];
            }
        }

        return $data[$locale];
    }

    private function setValue($locale, $property, $value): self
    {
        /** @var ReflectionProperty $reflectionProperty */
        $reflectionProperty = $this->attributes[$property];
        $reflectionProperty->setAccessible(true);

        $data = $reflectionProperty->getValue($this);

        if ($locale === 'all') {
            $data = $value;
        } else {
            $data[$locale] = $value;
        }

        $reflectionProperty->setValue($this, $data);

        return $this;
    }

    private function initAttributes()
    {
        if (false === $this->initialized) {
            $reflection = new ReflectionClass(get_called_class());

            foreach ($reflection->getProperties() as $property) {
                if (count($property->getAttributes(TranslatableField::class)) > 0) {
                    $this->attributes[$property->getName()] = $property;
                }
            }

            $this->initialized = true;
        }
    }
}