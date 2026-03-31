<?php

declare(strict_types=1);

namespace App\PatientAdmission\Infrastructure\Persistence\Doctrine;

use App\PatientAdmission\Domain\Entity\PatientAdmission;
use App\PatientAdmission\Domain\Repository\PatientAdmissionRepositoryInterface;
use App\PatientAdmission\Domain\ValueObject\PatientAdmissionStatus;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final readonly class DoctrinePatientAdmissionRepository implements PatientAdmissionRepositoryInterface
{
    private EntityRepository $repository;

    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
        $this->repository = $entityManager->getRepository(PatientAdmission::class);
    }

    public function save(PatientAdmission $patientAdmission): void
    {
        $this->entityManager->persist($patientAdmission);
        $this->entityManager->flush();
    }

    public function existsActiveAdmissionForPatient(string $patientId): bool
    {
        $count = $this->repository->createQueryBuilder('pa')
            ->select('COUNT(pa.id)')
            ->where('pa.patientId = :patientId')
            ->andWhere('pa.status = :status')
            ->setParameter('patientId', $patientId)
            ->setParameter('status', PatientAdmissionStatus::ADMITTED)
            ->getQuery()
            ->getSingleScalarResult()
        ;

        return (int) $count > 0;
    }
}
