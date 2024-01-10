<?php

// namespace App\Tests;

// use App\Entity\AcquisitionUnit;
// use App\Form\AddAcquisitionUnitFormType;
// use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

// class AddAcquisitionUnitFormTypeTest extends WebTestCase
// {
//     public function testSubmitValidData()
//     {
//         $client = static::createClient();

//         $acquisitionUnit = new AcquisitionUnit();

//         $form = $client->getContainer()->get('form.factory')->create(AddAcquisitionUnitFormType::class, $acquisitionUnit);

//         $formData = [
//             'name' => 'ESP-003',
//         ];

//         $form->submit($formData);

//         $this->assertTrue($form->isValid());

//         $this->assertEquals($acquisitionUnit, $form->getData());
//     }

// }
