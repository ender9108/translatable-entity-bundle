<?php
namespace EnderLab\TranslatableEntityBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TranslatableFieldTextareaType extends TranslatableFieldType
{
    public function getParent(): string
    {
        return TextareaType::class;
    }
}

