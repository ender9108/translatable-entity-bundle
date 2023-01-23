<?php

namespace EnderLab\TranslatableEntityBundle\Services;

class CurrentLocaleService
{
    private static string $currentLocale = 'en';
    private static string $fallbackLocale = 'en';
    private static string $currentTimezone = 'Europe/Paris';
    private static string $fallbackTimezone = 'Europe/Paris';
    private static array $availableLocales = [];
    private static array $availableTimezones = [];

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

    public static function getAvailableLocales(): array
    {
        return self::$availableLocales;
    }

    public static function setAvailableLocales(array $availableLocales): void
    {
        self::$availableLocales = $availableLocales;
    }

    public static function getAvailableTimezones(): array
    {
        return self::$availableTimezones;
    }

    public static function setAvailableTimezones(array $availableTimezones): void
    {
        self::$availableTimezones = $availableTimezones;
    }
}