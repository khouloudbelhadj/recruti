<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use DateTime;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_e',TextType::class, [
                'attr' => [
                    'placeholder' => 'Title',
                ]
            ])
            ->add('date_e', DateType::class, [
                'widget' => 'single_text',
                'html5'=>true,
                'attr'=>['min'=>(new DateTime())->format('Y-m-d')],
            ])
            ->add('heure_e',TextType::class, [
                'attr' => [
                    'placeholder' => 'Time',
                ]
            ])
            ->add('lieu_e',TextType::class, [
                'attr' => [
                    'placeholder' => 'Place',
                ]
            ])
            ->add('description',TextAreaType::class, [
                'attr' => [
                    'placeholder' => 'Description',
                ]
            ])
            ->add('image_e',FileType::class, [
                'label' => 'Select image of ivent' ,
                'data_class' => null,
                
            ])
            ->add('theme_e', ChoiceType::class, [
                'choices' => [
                    'Informatique ' => 'Informatique ',
                    'Santé' => 'Santé',
                    'Genie Civil' => 'Genie Civil',
                    'Electromecanique' => 'Electromecanique',

                ],
                'placeholder' => 'Select Theme',
            ])
            ->add('cantact_e',TextType::class, [
                'attr' => [
                    'placeholder' => 'Contact',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
