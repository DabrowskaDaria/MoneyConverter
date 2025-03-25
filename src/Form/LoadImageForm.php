<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoadImageForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('image', FileType::class, [
                'label' => 'chooseImage',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => "form-control"]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'loadImage',
                'attr' => ['class' => 'btn-submit']
            ])
            ->add('remove', SubmitType::class, [
                'label' => 'Usuń obraz',
                'attr' => ['class' => 'btn-submit']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults([]);
    }

}