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
            ->add('image', FileType::class, [
                'label' => 'Image (jpg, jpeg, png, webp)',
                'required' => false,
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
            ]);
            $builder->get('image')->addModelTransformer(new CallBackTransformer(
                function($image) {
                    return null;
                },
                function($image) {
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
