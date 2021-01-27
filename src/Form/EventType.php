<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => "Nom de l'évènement"])
            ->add('description', TextareaType::class, ['label' => 'Description'])
            ->add('datetime_start', DateTimeType::class, ['label' => 'Date et heure de début',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text'])
            ->add('datetime_end', DateTimeType::class, ['label' => 'Date et heure de fin',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text'])
            ->add('place', TextType::class, ['label' => 'Lieu'])
            ->add('image', FileType::class, [
                'label' => 'Image (jpg, jpeg, png, webp)',
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '2m',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp'
                        ],
                        'mimeTypesMessage' => 'Seuls les fichiers jpg, jpeg, png et webp sont acceptés',
                    ])
                ],
            ])
            ->add('maxParticipant', IntegerType::class, ['label' => 'Nombre de participants maximum (facultatif)',
             'required' => false])
            ;
            $builder->get('image')->addModelTransformer(new CallBackTransformer(
                function ($image) {
                    return null;
                },
                function ($image) {
                    return $image;
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
