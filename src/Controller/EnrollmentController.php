<?php

namespace App\Controller;

use App\Entity\Student;
use App\Entity\Course;
use App\Entity\Enrollment;
use App\Form\EnrollmentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/enrollment')]
final class EnrollmentController extends AbstractController
{
    #[Route(name: 'app_enrollment_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $enrollments = $entityManager
            ->getRepository(Enrollment::class)
            ->findBy([], ['enrollmentDate' => 'DESC']);

        return $this->render('enrollment/index.html.twig', [
            'enrollments' => $enrollments,
        ]);
    }

    #[Route('/new', name: 'app_enrollment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $students = $entityManager->getRepository(Student::class)->findBy([], ['name' => 'ASC']);
        $courses = $entityManager->getRepository(Course::class)->findBy([], ['title' => 'ASC']);

        if ($request->isMethod('POST')) {
            try {
                $studentIds = $request->request->all('students');
                $courseId = $request->request->get('course');

                if (empty($studentIds)) {
                    $this->addFlash('error', 'Please select at least one student.');
                    return $this->render('enrollment/new.html.twig', [
                        'students' => $students,
                        'courses' => $courses,
                    ]);
                }

                if (!$courseId) {
                    $this->addFlash('error', 'Please select a course.');
                    return $this->render('enrollment/new.html.twig', [
                        'students' => $students,
                        'courses' => $courses,
                    ]);
                }

                $course = $entityManager->getRepository(Course::class)->find($courseId);
                $enrolledCount = 0;
                $duplicateCount = 0;

                foreach ($studentIds as $studentId) {
                    $student = $entityManager->getRepository(Student::class)->find($studentId);
                    
                    // Check for existing enrollment
                    $existingEnrollment = $entityManager->getRepository(Enrollment::class)
                        ->findOneBy(['student' => $student, 'course' => $course]);

                    if (!$existingEnrollment && $student && $course) {
                        $enrollment = new Enrollment();
                        $enrollment->setStudent($student);
                        $enrollment->setCourse($course);
                        $enrollment->setEnrollmentDate(new \DateTime());
                        $entityManager->persist($enrollment);
                        $enrolledCount++;
                    } else {
                        $duplicateCount++;
                    }
                }

                $entityManager->flush();

                if ($enrolledCount > 0) {
                    $this->addFlash('success', "$enrolledCount student(s) enrolled successfully!");
                }
                if ($duplicateCount > 0) {
                    $this->addFlash('warning', "$duplicateCount student(s) were already enrolled in this course.");
                }

                return $this->redirectToRoute('app_enrollment_index');
            } catch (\Exception $e) {
                $this->addFlash('error', 'An error occurred while processing enrollments.');
            }
        }

        return $this->render('enrollment/new.html.twig', [
            'students' => $students,
            'courses' => $courses,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_enrollment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Enrollment $enrollment, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EnrollmentType::class, $enrollment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->flush();
                $this->addFlash('success', 'Enrollment updated successfully!');
                return $this->redirectToRoute('app_enrollment_index');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Error updating enrollment.');
            }
        }

        return $this->render('enrollment/edit.html.twig', [
            'enrollment' => $enrollment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_enrollment_delete', methods: ['POST'])]
    public function delete(Request $request, Enrollment $enrollment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $enrollment->getId(), $request->getPayload()->getString('_token'))) {
            try {
                $entityManager->remove($enrollment);
                $entityManager->flush();
                $this->addFlash('success', 'Enrollment deleted successfully!');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Error deleting enrollment.');
            }
        }

        return $this->redirectToRoute('app_enrollment_index');
    }
}
