<?php

namespace AppBundle\Form;

use AppBundle\Form\DataTransformer\StringPhoneNumberToArrayTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PhoneNumberType extends AbstractType
{
    private function makeDualText(FormBuilderInterface $builder, array $options)
    {
        $dialNumberBuilder = $builder
            ->create($options['second_name'], 'text', $options['second_options'])
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $event->setData(preg_replace('/\D/', '', $event->getData()));
            })
        ;

        $builder
            ->add($options['first_name'], 'text', $options['first_options'])
            ->add($dialNumberBuilder)
            ->addModelTransformer(new StringPhoneNumberToArrayTransformer(
                $options['first_name'],
                $options['second_name']
            ));
        ;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        switch ($options['widget']) {
            case 'dual_text':
                $this->makeDualText($builder, $options);
                break;
            case 'single_text':
                break;
            case 'choice_text':
                break;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'widget' => 'dual_text',
            'first_name' => 'dialcode',
            'first_options' => [],
            'second_name' => 'dialnumber',
            'second_options' => [],
        ]);
        
        $resolver->addAllowedValues('widget', [ 'single_text', 'dual_text', 'choice_text' ]);
    }

    public function getName()
    {
        return 'app_phone_number';
    }
}
