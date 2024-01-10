<?php
namespace App\Form;

use App\Model\SearchData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class SearchFormType extends  AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('q', TextType::class, [
                'attr' => [
                    'placeholder' => 'Recherche via un nom de salle'
                ],
                'empty_data' => '',
                'required' => false
            ])
            ->add('floors', ChoiceType::class, [
                'label' => 'Étage',
                'choices' => $this->getFloorChoices(),
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('acquisitionUnitState', ChoiceType::class, [
                'label' => 'État du système d\'acquisition',
                'choices' => $this->getAcquisitionUnitStateChoices(),
                'multiple' => true,
                'expanded' => true,
            ])
        ;
    }

    private function getFloorChoices(): array
    {
        $floors = [
            'Rez-de-chaussée' => 0,
            '1er étage' => 1,
            '2ème étage' => 2,
            '3ème étage' => 3,
        ];

        return $floors;
    }

    private function getAcquisitionUnitStateChoices(): array
    {
        $state = [
            'En attente d\'affectation' => 'En attente d\'affectation',
            'En attente d\'installation' => 'En attente d\'installation',
            'Dysfonctionnement' => 'Dysfonctionnement',
            'En panne' => 'En panne',
            'Opérationnel' => 'Opérationnel',
        ];
        return $state;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }


}
