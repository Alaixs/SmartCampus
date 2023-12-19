<?php

namespace App\Tests;

use App\Entity\Room;
use App\Form\AddRoomFormType;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AddRoomFormTypeTest extends WebTestCase
{
    /**
     * La méthode testSubmitValidData() verifie si le form est valide avec des données correct
     * @return void
     */
    public function testSubmitValidData()
    {
        $client = static::createClient();
    
        $room = new Room();
    
        $form = $client->getContainer()->get('form.factory')->create(AddRoomFormType::class, $room);
    
        $formData = [
            'name' => 'D301',
            'floor' => 3,
            'capacity' => 40,
            'hasComputers' => 0,    
            'area' => 50,
            'exposure' => 'north',
            'nbWindows' => 4
        ];
    
        $form->submit($formData);

        $this->assertTrue($form->isValid());
    
       // dump($form->getErrors(true, false));

    }

    /**
     * La méthode testSubmitNegativeCapacity() verifie si le form est invalide avec la valeur de la capacité d'une salle en negative
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
            'exposure' => 'north',
            'nbWindows' => 4
        ];

        $form->submit($formData);

        $this->assertFalse($form->isValid());
        // dump($form->getErrors(true, false));
    }

    /**
     * La méthode testSubmitNegativeAeraSize() verifie si le form est invalide avec la valeur de la taille d'une salle en negative
     * @return void
     */
    public function testSubmitNegativeAeraSize()
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
            'exposure' => 'north',
            'nbWindows' => 4
        ];

        $form->submit($formData);

        $this->assertFalse($form->isValid());
    }

    /**
     * La méthode testSubmitNullAeraSize() verifie si le form est invalide avec la valeur de la taille d'une salle en nulle
     * @return void
     */
    public function testSubmitNullAeraSize()
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
            'exposure' => 'north',
            'nbWindows' => 4
        ];

        $form->submit($formData);

        $this->assertFalse($form->isValid());
    }

    /**
     * La méthode testSubmitNegativeNumberOfWindows() verifie si le form est invalide avec la valeur du nombre de fenetre en negative
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
            'exposure' => 'north',
            'nbWindows' => -4
        ];

        $form->submit($formData);
        $this->assertFalse($form->isValid());
    }
}
