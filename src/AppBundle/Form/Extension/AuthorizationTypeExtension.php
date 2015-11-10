<?php

namespace AppBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class AuthorizationTypeExtension extends AbstractTypeExtension
{
    private $authorizer;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizer = $authorizationChecker;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (null === $options['role']
            || $this->authorizer->isGranted($options['role'])) {
            return;
        }

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            if (!$form->isRoot()) {
                $parent = $form->getParent();
                $parent->remove($form->getName());
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'role' => null,
        ]);
    }

    public function getExtendedType()
    {
        return 'form';
    }
}
