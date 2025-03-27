<?php

namespace App\Controller;

use App\Entity\Student;
use App\Entity\Enrollment;
use App\Form\StudentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/student')]
final class StudentController extends AbstractController
{
    #[Route(name: 'app_student_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $students = $entityManager
            ->getRepository(Student::class)
            ->findBy([], ['name' => 'ASC']);

        return $this->render('student/index.html.twig', [
            'students' => $students,
        ]);
    }

    #[Route('/new', name: 'app_student_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $student = new Student();
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->persist($student);
                $entityManager->flush();
                
                $this->addFlash('success', 'Student created successfully!');
                return $this->redirectToRoute('app_student_index');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Error creating student. Please try again.');
            }
        }

        return $this->render('student/new.html.twig', [
            'student' => $student,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_student_show', methods: ['GET'])]
    public function show(Student $student, EntityManagerInterface $entityManager): Response
    {
        // Get student's enrollments
        $enrollments = $entityManager
            ->getRepository(Enrollment::class)
            ->findBy(['student' => $student], ['enrollmentDate' => 'DESC']);

        return $this->render('student/show.html.twig', [
            'student' => $student,
            'enrollments' => $enrollments,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_student_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Student $student, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->flush();
                
                $this->addFlash('success', 'Student updated successfully!');
                return $this->redirectToRoute('app_student_index');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Error updating student. Please try again.');
            }
        }

        return $this->render('student/edit.html.twig', [
            'student' => $student,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_student_delete', methods: ['POST'])]
    public function delete(Request $request, Student $student, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$student->getId(), $request->getPayload()->getString('_token'))) {
            try {
                $entityManager->remove($student);
                $entityManager->flush();
                
                $this->addFlash('success', 'Student deleted successfully!');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Cannot delete student. They may be enrolled in courses.');
            }
        }

        return $this->redirectToRoute('app_student_index');
    }
}
