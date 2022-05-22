<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'votre prénom',
                'constraints' => new Assert\Length(array(
                    'min'        => 2,
                    'max'        => 10,
                    'minMessage' => "doit avoir au moins 2 caractères",
                    'maxMessage' => "doit avoir max 10 caractères"
                )),
                'required' => true,
                'attr' => [
                    'placeholder' => 'entrez votre prénom'
                ]
            ])
            ->add('lastname', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\NotNull(),
                ],
                'label' => 'votre nom',
                'required' => true,
                'attr' => [
                    'placeholder' => 'entrez votre nom'
                ]
            ])
            ->add('email', EmailType::class, [
                'required' => true
                ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'le mot de passe et la confirmation doivent être identique.',
                'label' => 'Confirmez votre mot de passe',
                'required' => true,
                'first_options' => [
                    'label' => 'votre mot de passe',
                    'attr' => [
                        'placeholder' => 'entrez votre mot de passe'
                        ]
                ],
                'second_options' => [
                    'label' => 'Confirmez votre mot de passe',
                    'attr' => [
                        'placeholder' => 'entrez de nouveau votre prénom'
                        ]
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => "S'inscrire"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
