<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('email', EmailType::class, [
            'label' => 'Email',
            'constraints' => [
                new NotBlank([
                    'message' => 'Поле email не должно быть пустым.',
                ]),
                new Email([
                    'message' => 'Введите корректный email.',
                ]),
            ],
        ])

            ->add('password', PasswordType::class, [
                'label' => 'Password',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Поле пароль не должно быть пустым.',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Пароль должен содержать минимум {{ limit }} символов.',
                        'max' => 999,
                    ]),
                    new Regex([
                        'pattern' => '/^\S+$/',
                        'message' => 'Пароль не должен содержать пробелы.',
                    ]),
                    new Regex([
                        'pattern' => '/\d/',
                        'message' => 'Пароль должен содержать хотя бы одну цифру.',
                    ]),
                    new Regex([
                        'pattern' => '/(?=.*[A-ZА-Я])/u',
                        'message' => 'Пароль должен содержать хотя бы одну заглавную букву.',
                    ]),
                    new Regex([
                        'pattern' => '/[!@#$%^&*(),.?":{}|<>]/',
                        'message' => 'Пароль должен содержать хотя бы один спецсимвол.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
