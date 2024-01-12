<?php
namespace App\Domain;


enum AcquisitionUnitState : string
{
    case OPERATIONNEL = "Opérationnel";
    case ATTENTE_AFFECTATION = "En attente d'affectation";
    case ATTENTE_INSTALLATION = "En attente d'installation";
    case DYSFONCTIONNEMENT = "Dysfonctionnement";
    case EN_PANNE = "En panne";
}
