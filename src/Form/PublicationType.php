<?php

namespace App\Form;

use App\Entity\Publication;
use App\Form\MediaType;
use App\Form\CommentaireType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Validator\Constraints\Length;
class PublicationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('contenu', TextareaType::class, [
            'label' => false,
            'attr' => [
                'style' => 'resize:none',
                'class' => 'form-control'
            ],
            'constraints' => [
                new Length([
                    'max' => 10,
                    'maxMessage' => 'Le contenu de la publication ne peut pas dépasser {{ limit }} caractères.',
                ]),
            ],
        ])
        ->add('media', CollectionType::class, [
            'entry_type' => MediaType::class, // Utilisez le formulaire MediaType pour les médias
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false, // Nécessaire pour que les médias soient ajoutés correctement
            'prototype' => true, // Activer le prototype pour les nouveaux éléments
            'label' => false,
            'attr' => [
                'class' => 'your-custom-class',
            ],
            'entry_options' => [
                // Options supplémentaires pour le formulaire de média s'il y en a
            ],
        ])
        ->add('save', SubmitType::class, [
            'label' => 'Publier',
            'attr' => ['class' => 'btn btn-primary float-right publish-button'] // Ajoutez la classe float-right pour aligner à droite
        ])
       ;
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Publication::class,
        ]);
    }
}
