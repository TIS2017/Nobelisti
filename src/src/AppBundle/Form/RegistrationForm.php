<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;

class RegistrationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('first_name', TextType::class, array(
                'constraints' => array(
                    new NotBlank(),
                    new Length(array('max' => 100)),
                ),
            ))
            ->add('last_name', TextType::class, array(
                'constraints' => array(
                    new NotBlank(),
                    new Length(array('max' => 100)),
                ),
            ))
            ->add('email', EmailType::class, array(
                'constraints' => array(
                    new NotBlank(),
                    new Length(array('max' => 100)),
                    new Email(),
                ),
            ))
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
