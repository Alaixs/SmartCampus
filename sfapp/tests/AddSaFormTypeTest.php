<?php

namespace App\Tests\Form;

use App\Entity\AcquisitionUnit;
use App\Form\AddSaFormType;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AddSaFormTypeTest extends WebTestCase
{
    public function testSubmitValidData()
    {
        // Créez un client pour utiliser la fabrique de formulaires
        $client = static::createClient();

        // Créez une instance de l'entité à partir de la classe
        $acquisitionUnit = new AcquisitionUnit();

        // Utilisez le conteneur du client pour obtenir la fabrique de formulaires
        $form = $client->getContainer()->get('form.factory')->create(AddSaFormType::class, $acquisitionUnit);

        // Définissez les données du formulaire que vous souhaitez tester
        $formData = [
            'number' => '9831',
        ];

        // Soumettez les données au formulaire directement
        $form->submit($formData);

        // Assurez-vous que le formulaire est synchronisé
        $this->assertTrue($form->isSynchronized());

        // Vérifiez que l'entité a été correctement mise à jour
        $this->assertEquals($acquisitionUnit, $form->getData());

        // Vous pouvez ajouter d'autres assertions en fonction de vos besoins de test
    }

}
