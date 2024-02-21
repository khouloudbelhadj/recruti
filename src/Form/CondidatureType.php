<?php

namespace App\Form;

use App\Entity\Condidature;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;



class CondidatureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        
          ->add('nom_c',TextType::class, [
            'attr' => [
                'placeholder' => 'Nom de condidat',
            ]
        ])
        ->add('email_c',TextType::class, [
            'attr' => [
                'placeholder' => 'Email de condidat',
            ]
        ])
        ->add('cv_c',FileType::class, [
            'label' => 'Select your CV' ,
            'data_class' => null,
            
        ])
        ->add('lettre_mo',TextAreaType::class, [
            'attr' => [
                'placeholder' => 'Lettre de motivation',
            ]
        ])

        
        
        ->add('offer', EntityType::class, [
            'class' => 'App\Entity\Offer',
            'placeholder' => 'Select the offer you want', 
        ])
         ;
    
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Condidature::class,
        ]);
    }
}
