<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\CallbackTransformer;

class BannerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
        ]);
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
