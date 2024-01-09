<?php

namespace App\DataFixtures;

use App\Domain\AcquisitionUnitState;
use App\Entity\AcquisitionUnit;
use App\Entity\Building;
use App\Entity\Room;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;



class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    
    public function load(ObjectManager $manager) : void
    {
        /*
         * Creates acquisition unit entities
         */
        $esp009 = new AcquisitionUnit();
        $esp009->setState(AcquisitionUnitState::OPERATIONNEL->value);
        $esp009->setName("ESP-009");
        $manager->persist($esp009);
        $manager->flush();

        $esp008 = new AcquisitionUnit();
        $esp008->setState(AcquisitionUnitState::ATTENTE_AFFECTATION->value);
        $esp008->setName("ESP-008");
        $manager->persist($esp008);
        $manager->flush();

        $esp010 = new AcquisitionUnit();
        $esp010->setState(AcquisitionUnitState::ATTENTE_INSTALLATION->value);
        $esp010->setName("ESP-010");
        $manager->persist($esp010);
        $manager->flush();

        $esp011 = new AcquisitionUnit();
        $esp011->setState(AcquisitionUnitState::DYSFONCTIONNEMENT->value);
        $esp011->setName("ESP-011");
        $manager->persist($esp011);
        $manager->flush();

        $esp007 = new AcquisitionUnit();
        $esp007->setState(AcquisitionUnitState::EN_PANNE->value);
        $esp007->setName("ESP-007");
        $manager->persist($esp007);
        $manager->flush();

        /*
         *  Creates room entities
        */
        $d207 = new Room();
        $d207->setName("D207");
        $d207->setArea(30);
        $d207->setCapacity(25);
        $d207->setFloor(2);
        $d207->setExposure("Nord");
        $d207->setHasComputers(true);
        $d207->setNbWindows(5);
        $d207->setAcquisitionUnit($esp009);
        $manager->persist($d207);
        $manager->flush();

        $d204 = new Room();
        $d204->setName("D204");
        $d204->setArea(25);
        $d204->setCapacity(30);
        $d204->setFloor(2);
        $d204->setExposure("Sud");
        $d204->setHasComputers(true);
        $d204->setNbWindows(4);
        $d204->setAcquisitionUnit($esp007);
        $manager->persist($d204);
        $manager->flush();

        $d101 = new Room();
        $d101->setName("D101");
        $d101->setArea(60);
        $d101->setCapacity(50);
        $d101->setFloor(1);
        $d101->setExposure("Ouest");
        $d101->setHasComputers(false);
        $d101->setNbWindows(5);
        $d101->setAcquisitionUnit($esp010);
        $manager->persist($d101);
        $manager->flush();

        $d102 = new Room();
        $d102->setName("D102");
        $d102->setArea(78);
        $d102->setCapacity(60);
        $d102->setFloor(1);
        $d102->setExposure("Est");
        $d102->setHasComputers(true);
        $d102->setNbWindows(6);
        $d102->setAcquisitionUnit($esp011);
        $manager->persist($d102);
        $manager->flush();

        $d105 = new Room();
        $d105->setName("D105");
        $d105->setArea(40);
        $d105->setCapacity(20);
        $d105->setFloor(1);
        $d105->setExposure("Est");
        $d105->setHasComputers(true);
        $d105->setNbWindows(64);
        $manager->persist($d105);
        $manager->flush();

        /*
         *  Create building entities
         */

        $bat1 = new Building();
        $bat1->setName('Département informatique');
        $bat1->setNbFloor(3);
        $manager->persist($bat1);
        $manager->flush();

        /*
         * Creates user entities
         */
        $yacine = new User();
        $yacine->setUsername("yacine");
        $hashedPassword = $this->passwordHasher->hashPassword($yacine, 'jesuisyacine');

        $yacine->setPassword($hashedPassword);
        $yacine->setRoles(['ROLE_ADMIN']);
        $manager->persist($yacine);
        $manager->flush();

        $technicien = new User();
        $technicien->setUsername("technicien");
        $hashedPassword = $this->passwordHasher->hashPassword($technicien, 'jesuistechnicien');

        $technicien->setPassword($hashedPassword);
        $technicien->setRoles(['ROLE_TECHNICIEN']);
        $manager->persist($technicien);
        $manager->flush();
    }
}
