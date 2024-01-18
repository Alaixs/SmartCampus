<?php
namespace App\Form;

use DateTime;
use App\Model\GraphData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class GraphFormType extends  AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
    $startDate = new DateTime();

    $endDate = new DateTime();
    $endDate->modify('+1 day');

        $builder
            ->add('type', ChoiceType::class, [
                'label' => 'Type',
                'choices' => [
                    'Humidité' => 'hum',
                    'Température' => 'temp',
                    'Co2' => 'co2',
                ],
                'data' => 'hum',
                'multiple' => false,
                'expanded' => true,
            ])
            ->add('startDate', DateType::class, [
                'label' => 'Date de début',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'data' => $startDate,
            ])
            
            ->add('endDate', DateType::class, [
                'label' => 'Date de fin',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'data' => $endDate,
            ])
            ->add('period', ChoiceType::class, [
                'label' => 'Période',
                'choices' => [
                    'Heure' => 'hour',
                    'Jour' => 'day',
                    'Semaine' => 'week',
                    'Mois' => 'month',
                    'Année' => 'year',
                ],
                'expanded' => true,
                'multiple' => false,
                'data' => 'hour',
            ]);

        $builder->add('submit', SubmitType::class, [
            'label' => 'Appliquer',
            'attr' => [
                'class' => 'btn-submit'
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver) : void
    {
        $resolver->setDefaults([
            'data_class' => GraphData::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }


}

