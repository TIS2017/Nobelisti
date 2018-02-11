<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RegistrationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('first_name', TextType::class)
            ->add('last_name', TextType::class)
            ->add('email', EmailType::class)
            ->add('event_choice', ChoiceType::class, array(
                'choices' => $options['data']['events'],
            ))
            ->add('subscribed', CheckboxType::class, array(
                'label' => 'Subscribe to our newsletter?',
                'required' => false,
            ))
            ->add('agree_with_conditions', CheckboxType::class, array(
                    'label' => 'Do you agree with processing your data?',
                    'required' => true,
            ))
            ->add('save', SubmitType::class, array('label' => 'Save'))
            ->setMethod('POST');
    }
}
