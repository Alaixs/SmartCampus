<?php

namespace App\Tests\Form;

use App\Entity\Room;
use App\Form\AddRoomFormType;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AddRoomFormTypeTest extends WebTestCase
{
    public function testSubmitValidData()
    {
        // Créez un client pour utiliser la fabrique de formulaires
        $client = static::createClient();

        // Créez une instance de l'entité à partir de la classe
        $room = new Room();

        // Utilisez le conteneur du client pour obtenir la fabrique de formulaires
        $form = $client->getContainer()->get('form.factory')->create(AddRoomFormType::class, $room);

        // Définissez les données du formulaire que vous souhaitez tester
        $formData = [
            'name' => 'D301',
            'floor' => 3,
            'capacity' => 40,
            'hasComputers' => 0,
            'area' => 50, 
            'exposure' => 'north',
            'nbWindows' => 4
        ];

        // Soumettez les données au formulaire directement
        $form->submit($formData);

        // Assurez-vous que le formulaire est synchronisé
        $this->assertTrue($form->isSynchronized());

        // Vérifiez que l'entité a été correctement mise à jour
        $this->assertEquals($room, $form->getData());

    }

}
