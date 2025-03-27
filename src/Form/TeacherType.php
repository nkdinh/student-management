<?php

namespace App\Form;

use App\Entity\Teacher;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class TeacherType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Enter teacher name'],
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a name']),
                    new Length(['min' => 2, 'max' => 100])
                ]
            ])
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Enter email address'],
                'constraints' => [
                    new NotBlank(['message' => 'Please enter an email']),
                    new Email(['message' => 'Please enter a valid email address'])
                ]
            ])
            ->add('specialization', ChoiceType::class, [
                'choices' => [
                    'Mathematics' => 'Mathematics',
                    'Physics' => 'Physics',
                    'Chemistry' => 'Chemistry',
                    'Biology' => 'Biology',
                    'Computer Science' => 'Computer Science',
                    'Literature' => 'Literature',
                    'History' => 'History',
                    'Geography' => 'Geography',
                    'Physical Education' => 'Physical Education',
                    'Art' => 'Art',
                    'Music' => 'Music'
                ],
                'attr' => ['class' => 'form-select'],
                'placeholder' => 'Select specialization',
                'constraints' => [new NotBlank(['message' => 'Please select a specialization'])]
            ])
            ->add('phone', TelType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter phone number (e.g., 0912345678)'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a phone number']),
                    new Length([
                        'min' => 10,
                        'max' => 15,
                        'minMessage' => 'Phone number must be at least {{ limit }} digits',
                        'maxMessage' => 'Phone number cannot be longer than {{ limit }} digits'
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Teacher::class,
        ]);
    }
}
