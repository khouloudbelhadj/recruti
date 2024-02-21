<?php

namespace App\Form;

use App\Entity\Offer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use DateTime;



class OfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre_o',TextType::class, [
                'attr' => [
                    'placeholder' => 'Titre',
                ]
            ])
            ->add('description_o',TextAreaType::class, [
                'attr' => [
                    'placeholder' => 'Description',
                ]
            ])
            ->add('type_o', ChoiceType::class, [
                'choices' => [
                    'Freelance' => 'Freelance',
                    'Job' => 'Job',
                    'Internship' => 'Internship',
                ],
                'placeholder' => 'Veuillez choisir le type',
            ])
            ->add('localisation_o',TextType::class, [
                'attr' => [
                    'placeholder' => 'Localisation',
                ]
            ])
            ->add('date_o', DateType::class, [
                'widget' => 'single_text',
                'html5'=>true,
                'attr'=>['min'=>(new DateTime())->format('Y-m-d')],
            ])
            ->add('dure_o',TextType::class, [
                'attr' => [
                    'placeholder' => 'Duree',
                ]
            ])
            ->add('salarie_o',TextType::class, [
                'attr' => [
                    'placeholder' => 'Salaire',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offer::class,
        ]);
    }
}
