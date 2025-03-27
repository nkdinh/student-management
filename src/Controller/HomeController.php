<?php

namespace App\Controller;

use App\Entity\Student;
use App\Entity\Course;
use App\Entity\Teacher;
use App\Entity\Enrollment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Get statistics
        $stats = [
            'totalStudents' => $entityManager->getRepository(Student::class)->count([]),
            'totalCourses' => $entityManager->getRepository(Course::class)->count([]),
            'totalTeachers' => $entityManager->getRepository(Teacher::class)->count([]),
            'totalEnrollments' => $entityManager->getRepository(Enrollment::class)->count([]),
        ];

        // Get recent enrollments
        $recentEnrollments = $entityManager->getRepository(Enrollment::class)
            ->findBy([], ['enrollmentDate' => 'DESC'], 5);

        // Get popular courses (courses with most enrollments)
        $popularCourses = $entityManager->getRepository(Course::class)
            ->createQueryBuilder('c')
            ->select('c, COUNT(e.id) as enrollCount')
            ->leftJoin('c.enrollments', 'e')
            ->groupBy('c.id')
            ->orderBy('enrollCount', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();

        return $this->render('home/index.html.twig', [
            'title' => 'Welcome to Student Management System',
            'stats' => $stats,
            'recentEnrollments' => $recentEnrollments,
            'popularCourses' => $popularCourses,
        ]);
    }
}
