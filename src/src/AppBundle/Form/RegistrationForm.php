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
        $labels = $options['data']['lang'];
        $builder
            ->add('first_name', TextType::class, array(
                'constraints' => array(
                    new NotBlank(),
                    new Length(array('max' => 100)),
                ),
                'label'=> $labels['first_name']
            ))
            ->add('last_name', TextType::class, array(
                'constraints' => array(
                    new NotBlank(),
                    new Length(array('max' => 100)),
                ),
                'label'=> $labels['last_name']
            ))
            ->add('email', EmailType::class, array(
                'constraints' => array(
                    new NotBlank(),
                    new Length(array('max' => 100)),
                    new Email(),
                ),
                'label'=> $labels['email']
            ))
            ->add('event_choice', ChoiceType::class, array(
                'choices' => $options['data']['events'],
                'label' => $labels['event_choice']
            ))
            ->add('subscribed', CheckboxType::class, array(
                'label' => $labels['subscribed'],
                'required' => false,
            ))
            ->add('agree_with_conditions', CheckboxType::class, array(
                    'label' => $labels['conditions_agreement'],
                    'required' => true,
            ))
            ->add('save', SubmitType::class, array('label' => $labels['register_button']))
            ->setMethod('POST');
    }
}
