<?php

namespace AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class MailingListFilterForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('nameEmail', TextType::class, array(
            'label' => 'Name / Email',
            'required' => false,
        ))
        ->add('event', TextType::class, array(
            'required' => false,
        ))
        ->add('isSubscribed', CheckboxType::class, array(
            'label' => 'Only subscribed',
            'required' => false,
        ))
        ->add('filter', SubmitType::class, array(
            'label' => 'Filter',
        ))
        ->setMethod('GET');
    }
}
