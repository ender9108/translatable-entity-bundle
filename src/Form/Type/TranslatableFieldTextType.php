<?php
namespace EnderLab\TranslatableEntityBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\TextType;

class TranslatableFieldTextType extends TranslatableFieldType
{
    public function getParent(): string
    {
        return TextType::class;
    }
}

