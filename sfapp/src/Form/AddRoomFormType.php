<?php

namespace App\Form;

use App\Entity\Room;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddRoomFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom de la salle*'
            ])
            ->add('floor', null, [
                'label' => 'Etage*'
            ])
            ->add('capacity', null, [
                'label' => 'Capacité maximale*'
            ])
            ->add('hasComputers', null, [
                'label' => 'Comporte des ordinateurs*'
            ])
            ->add('area', null, [
                'label' => 'Surface*'
            ])
            ->add('exposure', null, [
                'label' => 'Exposition (nord, sud, est, ouest)*'
            ])
            ->add('nbWindows', null, [
                'label' => 'Nombre de fenêtres*'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Room::class,
        ]);
    }
}
