<?php

namespace App\Form;

use App\Entity\Ressource;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use DateTime;


class RessourceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre_b', TextType::class,[
            'attr' => [
                'placeholder' => 'Resource Title',
            ]
        ])
        
            ->add('type_b', TextType::class,[
                'attr' => [
                    'placeholder' => 'Resource Type',
                ]
            ])
            ->add('date_publica_b', DateType::class)

            ->add('categorie_resso_b', TextType::class,[
                'attr' => [
                    'placeholder' => 'Field',
                ]
            ])
            ->add('description_b', TextAreaType::class,[
            'attr' => [
                'placeholder' => 'Resource Description',
            ]
        ])
            ->add('image_b_ressource', FileType::class, [
                'label' => 'Select an image of resource' ,
                'data_class' => null,
                
            ])
            ->add('biblio', EntityType::class, [
                'class' => 'App\Entity\Biblio',
                'placeholder' => 'Select Library', 
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ressource::class,
        ]);
    }
}
