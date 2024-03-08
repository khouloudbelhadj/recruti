<?php

namespace App\Form;

use App\Entity\Rendezvous;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Offer;
use App\Entity\Calendrier; // Assurez-vous d'importer l'entité Calendrier si ce n'est pas déjà fait

class RendezvousType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder
       
        
        ->add('dateRendez', DateTimeType::class, [
            'label' => 'Date of Appointment',
            'widget' => 'single_text',
            // other options if needed
        ])
        ->add('offer', EntityType::class, [
            'class' => Offer::class,
            'choice_label' => 'titre_o', // Utilisez l'attribut 'titre_o' de l'entité Offer comme étiquette de choix
            'label' => 'Offer Title', // Étiquette pour le champ
            'required' => true, // Champ requis
            'attr' => ['class' => 'form-control'], // Attributs HTML supplémentaires
        ])
        ->add('heureRendez')
        ->add('lieu')
        ->add('emailCondi')
        ->add('emailRepresen');
}


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rendezvous::class,
        ]);
    }
}
