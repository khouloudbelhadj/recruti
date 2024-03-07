<?php

namespace App\Form;

use App\Entity\Calendrier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\RendezvousType;

class CalendrierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('proprietaire', TextType::class)
            ->add('liste', TextType::class)
            ->add('rendezvouses', CollectionType::class, [
                'entry_type' => RendezvousType::class, // Utilisez le type de formulaire pour Rendezvous
                'allow_add' => true, // Autoriser l'ajout de nouveaux rendez-vous
                'by_reference' => false, // N'envoie pas les rendez-vous par référence
                'label' => 'Rendez-vous', // Étiquette du champ
                // Vous pouvez ajouter d'autres options selon vos besoins
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Calendrier::class,
        ]);
    }
}

