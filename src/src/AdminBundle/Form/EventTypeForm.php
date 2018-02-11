<?php

namespace AdminBundle\Form;

use AdminBundle\Entity\EventType;
use TemplateBundle\Controller\CustomTemplateController;
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
            ->add('slug', TextType::class, array(
                'attr' => array(
                    'readonly' => $options['read_only_slug'],
                ),
            ))
            ->add('template', ChoiceType::class, array(
                'choices' => CustomTemplateController::getTemplateNamesForForm(),
            ))
            ->add('save', SubmitType::class, array('label' => 'Save'))
            ->setMethod('POST');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => EventType::class,
            'read_only_slug' => false,
        ));
    }
}
