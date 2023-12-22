<?php

namespace App\Tests;

use App\Entity\Room;
use App\Form\AddRoomFormType;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AddRoomFormTypeTest extends WebTestCase
{
    /**
     * La méthode testSubmitValidData() vérifie si le form est valide avec des données correctes
     * @return void
     */
    public function testSubmitValidData()
    {
        $client = static::createClient();

        $room = new Room();

        $form = $client->getContainer()->get('form.factory')->create(AddRoomFormType::class, $room);

        $formData = [
            'name' => 'D500',
            'floor' => 3,
            'capacity' => 40,
            'hasComputers' => 0,
            'area' => 50,
            'exposure' => 'Nord',
            'nbWindows' => 4
        ];

        $form->submit($formData);

        $this->assertTrue($form->isValid());
    }

    /**
     * La méthode testSubmitNegativeCapacity() vérifie si le form est invalide avec la valeur de la capacité d'une salle en negative
     * @return void
     */
    public function testSubmitNegativeCapacity()
    {
        $client = static::createClient();

        $room = new Room();

        $form = $client->getContainer()->get('form.factory')->create(AddRoomFormType::class, $room);

        $formData = [
            'name' => 'D301',
            'floor' => 3,
            'capacity' => -4,
            'hasComputers' => 0,
            'area' => 50,
            'exposure' => 'Nord',
            'nbWindows' => 4
        ];

        $form->submit($formData);

        $this->assertFalse($form->isValid());
    }

    /**
     * La méthode testSubmitNegativeAreaSize() vérifie si le form est invalide avec la valeur de la taille d'une salle en negative
     * @return void
     */
    public function testSubmitNegativeAreaSize()
    {
        $client = static::createClient();

        $room = new Room();

        $form = $client->getContainer()->get('form.factory')->create(AddRoomFormType::class, $room);

        $formData = [
            'name' => 'D301',
            'floor' => 3,
            'capacity' => 40,
            'hasComputers' => 0,
            'area' => -4,
            'exposure' => 'Nord',
            'nbWindows' => 4
        ];

        $form->submit($formData);

        $this->assertFalse($form->isValid());
    }

    /**
     * La méthode testSubmitNullAreaSize() vérifie si le form est invalide avec la valeur de la taille d'une salle en nulle
     * @return void
     */
    public function testSubmitNullAreaSize()
    {
        $client = static::createClient();

        $room = new Room();

        $form = $client->getContainer()->get('form.factory')->create(AddRoomFormType::class, $room);

        $formData = [
            'name' => 'D301',
            'floor' => 3,
            'capacity' => 40,
            'hasComputers' => 0,
            'area' => 0 ,
            'exposure' => 'Nord',
            'nbWindows' => 4
        ];

        $form->submit($formData);

        $this->assertFalse($form->isValid());
    }

    /**
     * La méthode testSubmitNegativeNumberOfWindows() vérifie si le form est invalide avec la valeur du nombre de fenêtres en negative
     * @return void
     */
    public function testSubmitNegativeNumberOfWindows()
    {
        $client = static::createClient();

        $room = new Room();

        $form = $client->getContainer()->get('form.factory')->create(AddRoomFormType::class, $room);

        $formData = [
            'name' => 'D301',
            'floor' => 3,
            'capacity' => 40,
            'hasComputers' => 0,
            'area' => 40 ,
            'exposure' => 'Nord',
            'nbWindows' => -4
        ];

        $form->submit($formData);
        $this->assertFalse($form->isValid());
    }
}
