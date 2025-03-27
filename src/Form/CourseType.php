<?php

namespace App\Form;

use App\Entity\Course;
use App\Entity\Teacher;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Range;

class CourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter course title'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a course title']),
                    new Length([
                        'min' => 3,
                        'max' => 100,
                        'minMessage' => 'Course title must be at least {{ limit }} characters',
                        'maxMessage' => 'Course title cannot be longer than {{ limit }} characters'
                    ])
                ]
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter course description',
                    'rows' => 4
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a course description']),
                    new Length([
                        'max' => 1000,
                        'maxMessage' => 'Description cannot be longer than {{ limit }} characters'
                    ])
                ]
            ])
            ->add('credits', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter number of credits'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Please enter the number of credits']),
                    new Range([
                        'min' => 1,
                        'max' => 10,
                        'notInRangeMessage' => 'Credits must be between {{ min }} and {{ max }}'
                    ])
                ]
            ])
            ->add('teacher', EntityType::class, [
                'class' => Teacher::class,
                'choice_label' => 'name',
                'required' => false,
                'attr' => ['class' => 'form-control'],
                'placeholder' => 'Select a teacher'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Course::class,
        ]);
    }
}
