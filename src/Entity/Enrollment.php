<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Enrollment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Student::class, inversedBy: 'enrollments')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'RESTRICT')]
    private Student $student;

    #[ORM\ManyToOne(targetEntity: Course::class, inversedBy: 'enrollments')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'RESTRICT')]
    private Course $course;

    #[ORM\Column(type: 'date')]
    private \DateTimeInterface $enrollmentDate;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getStudent(): Student
    {
        return $this->student;
    }
    public function setStudent(Student $student): self
    {
        $this->student = $student;
        return $this;
    }

    public function getCourse(): Course
    {
        return $this->course;
    }
    public function setCourse(Course $course): self
    {
        $this->course = $course;
        return $this;
    }

    public function getEnrollmentDate(): \DateTimeInterface
    {
        return $this->enrollmentDate;
    }
    public function setEnrollmentDate(\DateTimeInterface $date): self
    {
        $this->enrollmentDate = $date;
        return $this;
    }
}
