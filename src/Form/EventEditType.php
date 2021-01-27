<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class EventEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => "Nom de l'évènement"])
            ->add('description', TextType::class, ['label' => 'Description'])
            ->add('datetime_start', DateTimeType::class, ['label' => 'Date et heure de début',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text'])
            ->add('datetime_end', DateTimeType::class, ['label' => 'Date et heure de fin',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text'])
            ->add('place', TextType::class, ['label' => 'Lieu'])
            ->add('maxParticipant', IntegerType::class, ['label' => 'Nombre de participants maximum (facultatif)', 'required' => false])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
