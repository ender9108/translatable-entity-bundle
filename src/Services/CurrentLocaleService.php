<?php

namespace EnderLab\TranslatableEntityBundle\Services;

class CurrentLocaleService
{
    private static string $currentLocale = 'en';
    private static string $currentTimezone = 'Europe/Paris';
    private static string $fallbackLocale = 'en';
    private static string $fallbackTimezone = 'Europe/Paris';

    public static function getCurrentLocale(): string
    {
        return self::$currentLocale;
    }

    public static function setCurrentLocale(string $locale): void
    {
        self::$currentLocale = $locale;
    }

    public static function getFallbackLocale(): string
    {
        return self::$fallbackLocale;
    }

    public static function setFallbackLocale(string $locale): void
    {
        self::$fallbackLocale = $locale;
    }

    public static function getCurrentTimezone(): string
    {
        return self::$currentTimezone;
    }

    public static function setCurrentTimezone(string $timezone): void
    {
        self::$currentTimezone = $timezone;
    }

    public static function getFallbackTimezone(): string
    {
        return self::$fallbackTimezone;
    }

    public static function setFallbackTimezone(string $timezone): void
    {
        self::$fallbackTimezone = $timezone;
    }
}