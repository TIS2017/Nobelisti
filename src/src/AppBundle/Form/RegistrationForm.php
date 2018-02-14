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
                'label'=>$options['data']['lang']['first_name']
            ))
            ->add('last_name', TextType::class, array(
                'constraints' => array(
                    new NotBlank(),
                    new Length(array('max' => 100)),
                ),
                'label'=>$options['data']['lang']['last_name']
            ))
            ->add('email', EmailType::class, array(
                'constraints' => array(
                    new NotBlank(),
                    new Length(array('max' => 100)),
                    new Email(),
                ),
                'label'=>$options['data']['lang']['email']
            ))
            ->add('event_choice', ChoiceType::class, array(
                'choices' => $options['data']['events'],
                'label' => $options['data']['lang']['event_choice']
            ))
            ->add('subscribed', CheckboxType::class, array(
                'label' => $options['data']['lang']['subscribed'],
                'required' => false,
            ))
            ->add('agree_with_conditions', CheckboxType::class, array(
                    'label' => $options['data']['lang']['conditions_agreement'],
                    'required' => true,
            ))
            ->add('save', SubmitType::class, array('label' => $options['data']['lang']['register_button']))
            ->setMethod('POST');
    }
}
