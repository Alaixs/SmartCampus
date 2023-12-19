<?php

namespace App\Form;

use App\Entity\AcquisitionUnit;
use App\Entity\Room;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AssignSAFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('SA', EntityType::class, [
                'class' => AcquisitionUnit::class,
                'required' => true,
                'label' => 'Numéro du SA',
                'query_builder' => function (EntityRepository $er) use ($builder) {
                    $room = $builder->getData();

                    return $er->createQueryBuilder('au')
                        ->where('au.state = :state')
                        ->orWhere('au = :currentSA')
                        ->setParameter('state', "En attente d'affectation")
                        ->setParameter('currentSA', $room->getSA());
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
