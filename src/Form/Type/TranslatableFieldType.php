<?php
namespace EnderLab\TranslatableEntityBundle\Form\Type;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TranslatableFieldType extends AbstractType
{
    private array $availablesLocales = ['en'];

    /*public function __construct(
        private ContainerInterface $container
    ) {
        if (
            $this->container->getParameter('translatable_entity.availables_locales') !== null &&
            is_array($this->container->getParameter('translatable_entity.availables_locales')) &&
            count($this->container->getParameter('translatable_entity.availables_locales')) > 0
        ) {
            $this->availablesLocales = $this->container->getParameter('translatable_entity.availables_locales');
        }
    }*/

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        dd($this, $builder, $options);

        foreach ($options['availables_locales'] as $locale) {
            $builder
                ->add($builder->getName().ucfirst($locale), TextType::class, [
                    'required' => $options['required'],
                    'label' => false
                ])
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'availables_locales' => $this->availablesLocales,
            'compound' => true
        ]);
    }
}

