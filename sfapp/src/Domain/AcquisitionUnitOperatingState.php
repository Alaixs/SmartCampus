<?php
namespace App\Domain;


enum AcquisitionUnitOperatingState : string
{
    case OPERATIONAL = "Opérationnel";
    case WAITING_FOR_ASSIGNMENT = "En attente d'affectation";
    case WAITING_FOR_INSTALLATION = "En attente d'installation";
    case FAILURE = "Dysfonctionnement";
    case OUT_OF_SERVICE = "En panne";
}
