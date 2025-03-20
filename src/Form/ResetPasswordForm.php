<?php

declare(strict_types=1);

namespace App\Form;

use App\Model\PasswordDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResetPasswordForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('password', PasswordType::class, [
            'label' => 'Podaj hasło',
            'attr' => ['class' => 'form-control']
        ])
            ->add('repeatPassword', PasswordType::class, [
                'label' => "Powtórz hasło",
                'attr' => ['class' => 'form-control']
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Zmień hasło',
                'attr' => ['class' => 'btn btn-register-login-remindPassword mt-2']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'dataClass' => PasswordDTO::class,
        ]);
    }
}