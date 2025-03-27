<?php

namespace App\Controller;

use App\Entity\Teacher;
use App\Entity\Course;
use App\Form\TeacherType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/teacher')]
final class TeacherController extends AbstractController
{
    #[Route(name: 'app_teacher_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $teachers = $entityManager
            ->getRepository(Teacher::class)
            ->findBy([], ['name' => 'ASC']);

        return $this->render('teacher/index.html.twig', [
            'teachers' => $teachers,
        ]);
    }

    #[Route('/new', name: 'app_teacher_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $teacher = new Teacher();
        $form = $this->createForm(TeacherType::class, $teacher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->persist($teacher);
                $entityManager->flush();
                
                $this->addFlash('success', 'Teacher created successfully!');
                return $this->redirectToRoute('app_teacher_index');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Error creating teacher. Please try again.');
            }
        }

        return $this->render('teacher/new.html.twig', [
            'teacher' => $teacher,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_teacher_show', methods: ['GET'])]
    public function show(Teacher $teacher, EntityManagerInterface $entityManager): Response
    {
        $assignedCourse = $entityManager
            ->getRepository(Course::class)
            ->findOneBy(['teacher' => $teacher]);

        return $this->render('teacher/show.html.twig', [
            'teacher' => $teacher,
            'assignedCourse' => $assignedCourse
        ]);
    }

    #[Route('/{id}/edit', name: 'app_teacher_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Teacher $teacher, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TeacherType::class, $teacher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->flush();
                
                $this->addFlash('success', 'Teacher updated successfully!');
                return $this->redirectToRoute('app_teacher_index');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Error updating teacher. Please try again.');
            }
        }

        return $this->render('teacher/edit.html.twig', [
            'teacher' => $teacher,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_teacher_delete', methods: ['POST'])]
    public function delete(Request $request, Teacher $teacher, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$teacher->getId(), $request->getPayload()->getString('_token'))) {
            try {
                $entityManager->remove($teacher);
                $entityManager->flush();
                
                $this->addFlash('success', 'Teacher deleted successfully!');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Cannot delete teacher. They may be assigned to courses.');
            }
        }

        return $this->redirectToRoute('app_teacher_index');
    }
}
