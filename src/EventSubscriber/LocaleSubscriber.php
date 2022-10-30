<?php

namespace EnderLab\TranslatableEntityBundle\EventSubscriber;

use EnderLab\TranslatableEntity\Services\CurrentLocaleService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Contracts\Translation\TranslatorInterface;
use function locale_accept_from_http;

class LocaleSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private TranslatorInterface $translator,
        private ContainerInterface $container,
        private array $availablesLocales = ['en'],
        private string $defaultLocale = 'en',
        private array $availablesTimezones = ['Europe/London'],
        private string $defaultTimezone = 'Europe/London'
    ) {
        if (
            $this->container->getParameter('translatable_entity.availables_locales') !== null &&
            is_array($this->container->getParameter('translatable_entity.availables_locales')) &&
            count($this->container->getParameter('translatable_entity.availables_locales')) > 0
        ) {
            $this->availablesLocales = $this->container->getParameter('translatable_entity.availables_locales');
        }

        if ($this->container->getParameter('translatable_entity.default_locale') !== null) {
            $this->defaultLocale = $this->container->getParameter('translatable_entity.default_locale');
        }

        if (
            $this->container->getParameter('translatable_entity.availables_timezones') !== null &&
            is_array($this->container->getParameter('translatable_entity.availables_timezones')) &&
            count($this->container->getParameter('translatable_entity.availables_timezones')) > 0
        ) {
            $this->availablesTimezones = $this->container->getParameter('translatable_entity.availables_timezones');
        }

        if ($this->container->getParameter('translatable_entity.default_timezone') !== null) {
            $this->defaultTimezone = $this->container->getParameter('translatable_entity.default_timezone');
        }
    }

    public function onRequestEvent(RequestEvent $event)
    {
        $request = $event->getRequest();
        $locale = $this->defaultLocale;
        $timezone = $this->defaultTimezone;

        if (!$request->hasPreviousSession()) {
            $locale = locale_accept_from_http($request->headers->get('Accept-Language'));

            if (false !== $locale) {
                $locale = explode('_', $locale)[0];

                if (!in_array($locale, $this->availablesLocales)) {
                    $locale = $this->defaultLocale;
                }
            }
        } else {
            if (!($locale = $request->query->get('_locale'))) {
                $locale = $request->getSession()->get('_locale', $this->defaultLocale);
            }
        }

        if (isset($this->availablesTimezones[$locale])) {
            $timezone = $this->availablesTimezones[$locale];
        }

        CurrentLocaleService::setCurrentLocale($locale);
        CurrentLocaleService::setFallbackLocale($this->defaultLocale);
        CurrentLocaleService::setCurrentTimezone($timezone);
        CurrentLocaleService::setFallbackTimezone($this->defaultTimezone);

        $request->setLocale($locale);
        $request->getSession()->set('_locale', $locale);
        $this->translator->setLocale($locale);
    }

    public static function getSubscribedEvents(): array
    {
         return [
            KernelEvents::REQUEST => [
                ['onRequestEvent', 3],
            ],
        ];
    }
}
