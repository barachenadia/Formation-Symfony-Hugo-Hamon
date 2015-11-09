<?php

namespace AppBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

class AppExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('dictionaries', $config['dictionaries']);
        $container->setParameter('game.difficulty', $config['difficulty']);
        $container->setParameter('game.word_length', $config['word_length']);
    }
}
