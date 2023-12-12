<?php

namespace App\DataFixtures;

use App\Entity\AcquisitionUnit;
use App\Entity\Room;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        /*
         * Creates acquisition unit entities
         */
        $SA1000 = new AcquisitionUnit();
        $SA1000->setState("En attente d'affectation");
        $SA1000->setNumber("SA1000");
        $manager->persist($SA1000);
        $manager->flush();

        $SA1001 = new AcquisitionUnit();
        $SA1001->setState("Operationnel");
        $SA1001->setNumber("SA1001");
        $manager->persist($SA1001);
        $manager->flush();

        $SA1002 = new AcquisitionUnit();
        $SA1002->setState("En attente d'installation");
        $SA1002->setNumber("SA1002");
        $manager->persist($SA1002);
        $manager->flush();

        $SA1003 = new AcquisitionUnit();
        $SA1003->setState("Dysfonctionnement");
        $SA1003->setNumber("SA1003");
        $manager->persist($SA1003);
        $manager->flush();

        /*
         *  Creates room entities
        */
        $D207 = new Room();
        $D207->setName("D207");
        $D207->setArea(30);
        $D207->setCapacity(25);
        $D207->setFloor(2);
        $D207->setExposure("Nord");
        $D207->setHasComputers(true);
        $D207->setNbWindows(5);
        $D207->setSA($SA1001);
        $manager->persist($D207);
        $manager->flush();

        $D204 = new Room();
        $D204->setName("D204");
        $D204->setArea(25);
        $D204->setCapacity(30);
        $D204->setFloor(2);
        $D204->setExposure("Sud");
        $D204->setHasComputers(true);
        $D204->setNbWindows(4);
        $D204->setSA($SA1002);
        $manager->persist($D204);
        $manager->flush();

        $D101 = new Room();
        $D101->setName("D101");
        $D101->setArea(60);
        $D101->setCapacity(50);
        $D101->setFloor(1);
        $D101->setExposure("Ouest");
        $D101->setHasComputers(false);
        $D101->setNbWindows(5);
        $manager->persist($D101);
        $manager->flush();

        $D102 = new Room();
        $D102->setName("D102");
        $D102->setArea(78);
        $D102->setCapacity(60);
        $D102->setFloor(1);
        $D102->setExposure("Est");
        $D102->setHasComputers(true);
        $D102->setNbWindows(6);
        $D102->setSA($SA1003);

        $manager->persist($D102);
        $manager->flush();
    }
}
