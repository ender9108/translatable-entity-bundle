<?php
namespace EnderLab\TranslatableEntityBundle\Twig;

use EnderLab\TranslatableEntityBundle\Services\CurrentLocaleService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TranslatableFieldExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('availableLocales', fn(): array => $this->getAvailableLocales()),
            new TwigFunction('availableTimezones', fn(): array => $this->getAvailableTimezones()),
            new TwigFunction('currentLocale', fn(): string => $this->getCurrentLocale()),
            new TwigFunction('currentTimezone', fn(): string => $this->getCurrentTimezone()),
        ];
    }

    public function getAvailableLocales(): array
    {
        return CurrentLocaleService::getAvailableLocales();
    }

    public function getAvailableTimezones(): array
    {
        return CurrentLocaleService::getAvailableTimezones();
    }

    public function getCurrentLocale(): string
    {
        return CurrentLocaleService::getCurrentLocale();
    }

    public function getCurrentTimezone(): string
    {
        return CurrentLocaleService::getCurrentTimezone();
    }
}
