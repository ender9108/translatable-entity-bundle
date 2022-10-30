<?php
namespace EnderLab\TranslatableEntityBundle;

use EnderLab\TranslatableEntity\DependencyInjection\TranslatableEntityExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class TranslatableEntityBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $extension = new TranslatableEntityExtension([], $container);
    }
}