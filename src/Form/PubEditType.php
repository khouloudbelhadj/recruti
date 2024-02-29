<?php

namespace App\Form;

use App\Entity\Publication;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\OptionsResolver\OptionsResolver;

class PubEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           // ->add('contenu')
           ->add('contenu', TextType::class, [
            'label' => false,
            'attr' => ['class' => 'form-control'] // Ajoutez les classes CSS Bootstrap ici
        ])
        
        ->add('save', SubmitType::class, [
            'label' => 'Enregistrer',
            'attr' => ['class' => 'btn btn-primary'] // Ajout de classes CSS au bouton
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
