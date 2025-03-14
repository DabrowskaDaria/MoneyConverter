<?php

namespace App\Form;

use App\Model\UserDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'name', TextType::class,[
                'label' => 'Imię',
                'attr'=>['class'=>'form-control', 'id' => 'name']
            ])
            ->add( 'surname',TextType::class,[
                'label' => 'Nazwisko',
                'attr' => ['class'=>'form-control']
            ])
            ->add('email',EmailType::class,[
                'label' =>'E-mail',
                'attr' => ['class' => "form-control"]
            ])
            ->add('password',PasswordType::class,[
                'label' => 'Hasło',
                'attr' =>   ['class' => "form-control"]
            ])
            -> add('submit', SubmitType::class,[
                'label' => 'Zarejestruj się',
                'attr' =>['class' => 'btn btn-register-login-remindPassword']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserDTO::class,
        ]);
    }
}