<?php

namespace AdminBundle\Form;

use AdminBundle\Entity\Event;
use TemplateBundle\Controller\CustomTemplateController;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EventForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('address', TextType::class)
            ->add('date_time', DateTimeType::class)
            ->add('notification_threshold', IntegerType::class)
            ->add('capacity', NumberType::class)
            ->add('registration_end', DateTimeType::class)
            ->add('template_override', ChoiceType::class, array(
                'choices' => CustomTemplateController::getTemplateNamesForForm(),
            ))
            ->add('save', SubmitType::class, array('label' => 'Save'))
            ->setMethod('POST');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Event::class,
        ));
    }
}
