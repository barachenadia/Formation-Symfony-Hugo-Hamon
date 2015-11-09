<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('senderName', 'text')
            ->add('senderEmailAddress', 'email')
            ->add('subject', 'text')
            ->add('message', 'textarea', [
                'attr' => [
                    'cols' => 50,
                    'rows' => 15,
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Model\Contact',
            'foobar' => 'toto',
        ]);
    }

    public function getName()
    {
        return 'app_contact';
    }
}
