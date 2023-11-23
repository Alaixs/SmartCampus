<?php

namespace App\Tests\Form;

use App\Entity\AcquisitionUnit;
use App\Form\AddSaFormType;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AddSaFormTypeTest extends WebTestCase
{
    public function testSubmitValidData()
    {
        $client = static::createClient();

        $acquisitionUnit = new AcquisitionUnit();

        $form = $client->getContainer()->get('form.factory')->create(AddSaFormType::class, $acquisitionUnit);

        $formData = [
            'number' => '9831',
        ];

        $form->submit($formData);

        $this->assertTrue($form->isValid());

        $this->assertEquals($acquisitionUnit, $form->getData());

        dump($acquisitionUnit, $form->getData());

    }

}
