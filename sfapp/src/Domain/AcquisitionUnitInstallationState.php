<?php
namespace App\Domain;

enum AcquisitionUnitInstallationState : string
{
    case NOT_ASSIGNED = "Non assigné";
    case ASSIGNED = "Assigné";
    case SUPPORTED = "Pris en charge";
    case SET_UP = "Mis en place";
}