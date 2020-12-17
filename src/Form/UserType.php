<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\CallbackTransformer;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, ['label' => "Prénom"])
            ->add('lastname', TextType::class, ['label' => "Nom"])
            ->add('birthday', BirthdayType::class, ['label' => "Date de naissance"])
            ->add('email', EmailType::class, ['label' => "Adresse mail"])
            ->add('phone_number', TextType::class, ['label' => "Numéro de téléphone"])
            ->add('address', TextType::class, ['label' => "Adresse (numéro, voie, code postal, ville)"])
            ->add('avatar', FileType::class, [
                'label' => 'Avatar (jpg, jpeg, png, webp)',
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
            $builder->get('avatar')->addModelTransformer(new CallBackTransformer(
                function($avatar) {
                    return null;
                },
                function($avatar) {
                    return $avatar;
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
