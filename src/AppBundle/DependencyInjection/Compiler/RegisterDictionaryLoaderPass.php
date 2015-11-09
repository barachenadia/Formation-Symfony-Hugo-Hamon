<?php

namespace AppBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use AppBundle\Game\Loader\LoaderInterface;

class RegisterDictionaryLoaderPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('app.word_list')) {
            return;
        }

        $definition = $container->getDefinition('app.word_list');
        foreach ($container->findTaggedServiceIds('hangman.dictionary_loader') as $id => $tags) {
            $tag = $tags[0];
            $type = $tag['type'];

            $loaderDefinition = $container->findDefinition($id);
            $class = $loaderDefinition->getClass();
            $interface = LoaderInterface::class;

            $rc = new \ReflectionClass($class);
            if (!$rc->implementsInterface($interface)) {
                throw new \RuntimeException(sprintf(
                    'Service "%s" of type "%s" must implement "%s" interface.',
                    $id,
                    $class,
                    $interface
                ));
            }

            $definition->addMethodCall('addLoader', [ $type, new Reference($id) ]);
        }
    }
}
