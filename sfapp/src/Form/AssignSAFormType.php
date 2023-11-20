<?php

namespace App\Form;

use App\Entity\AcquisitionUnit;
use App\Entity\Room;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AssignFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('SA', EntityType::class, [
                'class' => AcquisitionUnit::class,
                'required' => true,
                'label' => 'Numéro du SA',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('au')
                        ->where('au.state = :state')
                        ->setParameter('state', 'En attente');
                },
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
