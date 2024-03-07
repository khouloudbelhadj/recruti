<?php

namespace App\Form;

use App\Entity\Biblio;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints as Assert;

use DateTime;

class BiblioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_b', TextType::class,[
            'attr' => [
                'placeholder' => 'Library Name',
            ]
            ])  

            ->add('domaine_b', TextType::class,[
            'attr' => [
                'placeholder' => 'Library Field',
            ]
            ])             

            ->add('date_creation_b', DateType::class)

            ->add('image_b', FileType::class, [
                'label' => 'Select an image of Library' ,
                'data_class' => null,
                
            ]);
        }

        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => Biblio::class,
            ]);
        }
    }