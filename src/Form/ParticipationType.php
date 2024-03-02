<?php

namespace App\Form;

use App\Entity\Participation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ParticipationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('role', ChoiceType::class, [
                'choices' => [
                    'Participant actif ' => 'Participant actif ',
                    'Bénévole' => 'Bénévole',
                    'Observateur' => 'Observateur',
                    'Expert' => 'Expert',
                ],
                'placeholder' => 'Select role',
            ])
            ->add('statut',ChoiceType::class, [
                'choices' => [
                    'Confirmé' => 'Confirmé',
                    'Non confirmé ' => 'Non confirmé ',
                    'Présent virtuellement' => 'Présent virtuellement',
                ],
                'placeholder' => 'Select Status',
            ])
            ->add('feedback',TextAreaType::class, [
                'attr' => [
                    'placeholder' => 'feedback',
                ]
            ])
            ->add('nom_participant',TextType::class, [
                'attr' => [
                    'placeholder' => 'Name',
                ]
            ])
            ->add('event', EntityType::class, [
                'class' => 'App\Entity\Event',
                'placeholder' => 'Select Event', 
            ])
             
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participation::class,
        ]);
    }
}
