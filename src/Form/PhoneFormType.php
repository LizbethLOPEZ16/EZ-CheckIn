<?php

namespace App\Form;

use App\Entity\Phone;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class PhoneFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('country_code', ChoiceType::class, [
                'label' => false,
                'placeholder' => '',
                'choices' => [
                    '+52' => '+52',
                    '+1' => '+1',
                ],
                'attr' => [
                    'class' => 'form-control-lg',
                ],
                'help' => 'Lada'
            ])
            ->add('phone_number', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control-lg',
                ],
                'help' => 'Numero'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Phone::class,
        ]);
    }
}
