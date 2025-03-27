<?php

namespace App\Repository;

use App\Entity\Course;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CourseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Course::class);
    }

    public function hasEnrollments(Course $course): bool
    {
        return !$this->createQueryBuilder('c')
            ->select('COUNT(e.id)')
            ->leftJoin('c.enrollments', 'e')
            ->where('c.id = :courseId')
            ->setParameter('courseId', $course->getId())
            ->getQuery()
            ->getSingleScalarResult() == 0;
    }
}
