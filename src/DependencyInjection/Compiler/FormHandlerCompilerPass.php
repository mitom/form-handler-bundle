<?php

namespace Mitom\Bundle\FormHandlerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class FormHandlerCompilerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $formFactory = new Reference('form.factory');
        $manager = $container->getDefinition('mitom_form_handler.manager');
        $taggedServices = $container->findTaggedServiceIds('mitom.form_handler');
        foreach ($taggedServices as $id => $attrs) {
            $definition = $container->getDefinition($id);
            $definition->addMethodCall('setFormFactory', [$formFactory]);
            $manager->addMethodCall(
                'addHandler',
                [new Reference($id)]
            );

        }
    }
}
