<?php

namespace App\Form;

use App\Entity\Commentaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        //     ->add('contenu_com')
        //     ->add('date_creationCom')
        //     ->add('publication')
        //     ->add('user')
        // ;
        ->add('contenu_com', TextareaType::class, [
            'label' => false,
            'attr' => [
                'style' => 'resize:none',
                'class' => 'form-control'
            ],
           
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentaire::class,
        ]);
    }
}
