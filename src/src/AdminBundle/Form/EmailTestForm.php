<?php

namespace AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class EmailTestForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('first_name', TextType::class, ["data" => "John"])
            ->add('last_name', TextType::class, ["data" => "Smith"])
            ->add('email', EmailType::class)
            ->add('language', ChoiceType::class, array(
                'choices' => $options['data'],
            ))
            ->add('submit', SubmitType::class)
            ->setMethod('POST');
    }
}
