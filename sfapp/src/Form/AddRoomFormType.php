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
                'attr' => array(
                    'placeholder' => 'Exemple : D203'
                )
            ])

            ->add('floor', ChoiceType::class, [
                'required' => true,
                'label' => 'Étage',
                'row_attr' => ['class' => 'rolling_menu'],
                'choices' => [
                    'Rez de chaussée' => 0,
                    'Premier étage' => 1,
                    'Deuxième étage' => 2,
                    'Troisième étage' => 3
                ],
                'label' => 'Étages '
            ])

            ->add('capacity', null, [
                'required' => true,
                'label' => 'Capacité maximale',
                'attr' => array(
                    'placeholder' => 'Exemple : 30'
                )
            ])

            ->add('hasComputers', null, [
                'required' => false,
                'label' => 'Comporte des ordinateurs'
            ])

            ->add('area', null, [
                'required' => true,
                'label' => 'Surface',
                'attr' => array(
                    'placeholder' => 'Exemple : 60'
                )
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
                'label' => 'Nombre de fenêtres',
                'attr' => array(
                    'placeholder' => 'Exemple : 3'
                )
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {

        $resolver->setDefaults([
            'data_class' => Room::class,
        ]);
    }
}
