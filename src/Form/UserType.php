<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * The TaskType class is used to generate the form bind to the Task entity.
 *
 * Class UserType
 * @package App\Form
 */
class UserType extends AbstractType
{
    /**
     * The buildForm() method is a customizable factory for the form that you want.
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class,
                [
                    'label' => 'Nom d\'utilisateur'
                ]
            )
            ->add('email', EmailType::class,
                [
                    'label' => "Adresse email"
                ]
            )

            ->add('password', RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'invalid_message' => 'Les deux mots de passe ne correspondent pas',
                    'required' => true,
                    'first_options' =>
                        [
                            'label' => 'Mot de passe'
                        ],
                    'second_options' =>
                        [
                            'label' => 'Confirmer le mot de passe'
                        ],
                ]
            );
    }

    /**
     * The configurationOption() method is simply the configuration for the form.
     *
     * There are many options that you can use, such as __*data_class*__ which is the entity bound to the form.
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'allow_extra_fields',
                'data_class' => User::class
            ]
        );
    }
}
