<?php

namespace App\Form;

use App\Entity\Room;
use \App\Form\MyFormFormatType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;

class AddRoomFormType extends MyFormFormatType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'required' => true,
                'label' => 'Nom de la salle ',
            ])

            ->add('floor', null, [
                'required' => true,
                'label' => 'Etage'
            ])
            ->add('capacity', null, [
                'required' => true,
                'label' => 'Capacité maximale'
            ])
            ->add('hasComputers', null, [
                'required' => true,
                'label' => 'Comporte des ordinateurs'
            ])
            ->add('area', null, [
                'required' => true,
                'label' => 'Surface'
            ])
            ->add('exposure', ChoiceType::class, [
                'required' => true,
                'choices' => [
                    'Nord' => 'north',
                    'Sud' => 'south',
                    'Est' => 'east',
                    'Ouest' => 'west'
                ],

                'label' => 'Exposition'
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
