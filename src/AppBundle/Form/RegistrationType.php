<?php

namespace AppBundle\Form;

use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', 'text')
            ->add('fullName', 'text')
            ->add('emailAddress', 'email')
            ->add('password', 'repeated', [
                'type' => 'password',
                'first_name' => 'password',
                'second_name' => 'confirmation',
                'invalid_message' => 'user.passwords.mismatch',
            ])
            ->add('birthdate', 'birthday')
            ->add('rules', 'checkbox', [
                'mapped' => false,
                'label' => 'Accept terms and conditions',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'validation_groups' => [ 'Default', 'Signup' ]
        ]);
    }

    public function getName()
    {
        return 'app_registration';
    }
}
