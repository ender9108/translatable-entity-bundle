<?php

namespace EnderLab\TranslatableEntityBundle\Form\Type;

use EnderLab\TranslatableEntityBundle\Services\CurrentLocaleService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class TranslatableFieldType extends AbstractType implements DataTransformerInterface, DataMapperInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /*foreach ($options['availables_locales'] as $locale) {
            $builder
                ->add($builder->getName().ucfirst(strtolower($locale)), TextType::class, [

                ])
            ;
        }*/

        $builder
            ->addViewTransformer($this)
            ->setDataMapper($this)
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($options) {
                $form = $event->getForm();

                foreach ($options['availables_locales'] as $locale) {
                    $name = strtr($form->getName(), ['All' => '']);

                    $form
                        ->add($name.ucfirst(strtolower($locale)), TextType::class, [
                            'required' => $locale === $options['current_locale'],
                            'label' => false
                        ])
                    ;
                }
            })
        ;
    }

    public function mapDataToForms($viewData, iterable $forms)
    {
        if (null === $viewData) {
            return;
        }

        if (!is_array($viewData)) {
            throw new UnexpectedTypeException($viewData, 'array');
        }

        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);

        foreach ($viewData as $locale => $data) {
            foreach ($forms as $field => $form) {
                if (str_ends_with($field, ucfirst($locale))) {
                    $form->setData($data);
                }
            }
        }
    }

    public function mapFormsToData(iterable $forms, &$viewData)
    {
        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);

        foreach ($forms as $field => $form) {
            $locale = strtolower(substr($field, -2));

            if (isset($viewData[$locale])) {
                $viewData[$locale] = $form->getData();
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'availables_locales' => CurrentLocaleService::getAvailableLocales(),
            'current_locale' => CurrentLocaleService::getCurrentLocale(),
            'allow_extra_fields' => true,
            'compound' => true,
        ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $object = $form->getParent()->getData();
        $view->vars['object'] = $object;
        $view->vars['availables_locales'] = $options['availables_locales'];
        $view->vars['current_locale'] = $options['current_locale'];
    }

    public function getBlockPrefix(): string
    {
        return 'translatable_field';
    }

    /**
     * {@inheritdoc}
     */
    public function transform(mixed $data): mixed
    {
        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform(mixed $data): mixed
    {
        return $data ?? '';
    }
}