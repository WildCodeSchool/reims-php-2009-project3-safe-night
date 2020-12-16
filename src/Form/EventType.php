<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => "Nom de l'évènement"])
            ->add('description', TextType::class, ['label' => 'Description'])
            ->add('datetime_start', DateTimeType::class, ['label' => 'Date et heure de début'])
            ->add('datetime_end', DateTimeType::class, ['label' => 'Date et heure de fin'])
            ->add('place', TextType::class, ['label' => 'Lieu'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
