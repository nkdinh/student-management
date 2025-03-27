<?php

namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use App\Entity\Enrollment;
use App\Entity\Student;
use App\Entity\Course;

class EnrollmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('course', EntityType::class, [
                'class' => Course::class,
                'choice_label' => 'title',  // Display course title
                'placeholder' => 'Choose a Course',
                'required' => true,
            ])
            ->add('students', EntityType::class, [
                'class' => Student::class,
                'choice_label' => 'name',  // Display student name
                'multiple' => true,  // Allow multiple selections
                'expanded' => false, // Display as a dropdown (change to true for checkboxes)
                'required' => true,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enroll Students'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null, // No specific entity, we handle it manually
        ]);
    }
}
