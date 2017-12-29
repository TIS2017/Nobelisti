<?php

namespace AdminBundle\Form;

use AdminBundle\Entity\EventType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EventTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('slug', TextType::class)
            ->add('template', ChoiceType::class, array(
                'choices' => array(
                    'template1' => 'var/www/templates/template1',
                    'template2' => 'var/www/templates/template2',
                    'template3' => 'var/www/templates/template3',
                )
            ))
            ->add('save', SubmitType::class, array('label' => 'Save'))
            ->setMethod('POST');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => EventType::class,
        ));
    }
}
