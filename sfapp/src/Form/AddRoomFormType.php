<?php

namespace App\Form;

use App\Entity\Room;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;

class AddRoomFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'required' => true,
                'label' => 'Nom de la salle ',
            ])

            ->add('floor', ChoiceType::class, [
                'required' => true,
                'row_attr' => ['class' => 'rolling_menu'],
                'choices' => [
                    'Rez de chaussée' => 0,
                    'Premier étage' => 1,
                    'Deuxième étage' => 2,
                    'Troisième étage' => 3
                ],
                'label' => 'Etage',

                
            ])
            ->add('capacity', null, [
                'required' => true,
                'label' => 'Capacité maximale'
            ])
            ->add('hasComputers', null, [
                'required' => false,
                'label' => 'Comporte des ordinateurs'
            ])
            ->add('area', null, [
                'required' => true,
                'label' => 'Surface (en m²)'
            ])
            ->add('exposure', ChoiceType::class, [
                'required' => true,
                'row_attr' => ['class' => 'rolling_menu'],
                'label' => 'Exposition',
                'choices' => [
                    'Nord' => 'north',
                    'Sud' => 'south',
                    'Est' => 'east',
                    'Ouest' => 'west'
                ],

            ])
            ->add('nbWindows', null, [
                'required' => true,
                'label' => 'Nombre de fenêtres'
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {

        $resolver->setDefaults([
            'data_class' => Room::class,
        ]);
    }
}
