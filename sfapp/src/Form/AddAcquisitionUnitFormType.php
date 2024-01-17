<?php

namespace App\Form;

use App\Entity\AcquisitionUnit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AddAcquisitionUnitFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'required' => true,
                'label' => 'Numéro du SA',
                'attr' => [
                    'placeholder' => 'Exemple : 123',
                    'maxlength' => 7,
                    'value' => 'ESP-'
                ],
            ]);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            if (!empty($data['name']) && !str_starts_with($data['name'], 'ESP-')) {
                $data['name'] = 'ESP-' . $data['name'];
                $event->setData($data);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AcquisitionUnit::class,
        ]);
    }
}

