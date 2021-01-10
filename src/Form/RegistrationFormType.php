<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\CallbackTransformer;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('firstname', TextType::class, ['label' => "Prénom"])
        ->add('lastname', TextType::class, ['label' => "Nom"])
        ->add('birthday', BirthdayType::class, ['label' => "Date de naissance"])
        ->add('email', EmailType::class, ['label' => "Adresse mail"])
        ->add('plainPassword', PasswordType::class, [
            // instead of being set onto the object directly,
            // this is read and encoded in the controller
            'mapped' => false,
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez entrer un mot de passe',
                ]),
                new Length([
                    'min' => 6,
                    'minMessage' => 'Votre mot de passe devrait faire au moins {{ limit }} caractères',
                    // max length allowed by Symfony for security reasons
                    'max' => 4096,
                ]),
            ],
            'label' => 'Mot de passe'
        ])
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
        ])
        ->add('agreeTerms', CheckboxType::class, [
            'mapped' => false,
            'constraints' => [
                new IsTrue([
                    'message' => "Il est nécessaire d'accepter nos termes",
                ]),
            ],
            'label' => 'Accepter nos termes de confidentialité'
        ]);
        $builder->get('avatar')->addModelTransformer(new CallBackTransformer(
            function ($avatar) {
                return null;
            },
            function ($avatar) {
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
