<?php

namespace App\Form;

use App\Entity\Person;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;

class PersonFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control-lg',
                ],
                'help' => 'Nombre(s)',
                'required' => false
            ])
            ->add('last_name', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control-lg',
                ],
                'help' => 'Apellido(s)',
                'required' => false
            ])
            ->add('dob', BirthdayType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control-lg',
                ],
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Person::class,
        ]);
    }
}
