<?php

declare(strict_types=1);

namespace App\PatientAdmission\Domain\ValueObject;

enum PatientAdmissionStatus: string
{
    case ADMITTED = 'ADMITTED';
    case DISCHARGED = 'DISCHARGED';
    case CANCELLED = 'CANCELLED';
}
