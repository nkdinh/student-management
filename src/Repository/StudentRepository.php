<?php

namespace App\Repository;

use App\Entity\Student;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class StudentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Student::class);
    }

    public function hasEnrollments(Student $student): bool
    {
        return !$this->createQueryBuilder('s')
            ->select('COUNT(e.id)')
            ->leftJoin('s.enrollments', 'e')
            ->where('s.id = :studentId')
            ->setParameter('studentId', $student->getId())
            ->getQuery()
            ->getSingleScalarResult() == 0;
    }
}
