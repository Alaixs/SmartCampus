<?php

namespace App\Tests\Form;

use App\Entity\Room;
use App\Form\AddRoomFormType;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AddRoomFormTypeTest extends WebTestCase
{
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
    
}
