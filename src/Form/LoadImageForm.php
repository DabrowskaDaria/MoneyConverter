<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class LoadImageForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('image', FileType::class, [
                'label' => 'chooseImage',
                'mapped' => false,
                'required' => true,
                'attr' => ['class' => "form-control"],
                'constraints' => [
                    new File([
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Please upload a valid image file',
                    ])
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'loadImage',
                'attr' => ['class' => 'btn btn-submit mt-2']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults([]);
    }

}