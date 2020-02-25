<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

final class StudentAdmin extends AbstractAdmin
{

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('id')
            ->add('name')
            ->add('school_name')
            ->add('schedule')
            ->add('grade')
            ->add('id_aleatorio')
            ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('id')
            ->add('name')
            ->add('school_name')
            ->add('schedule')
            ->add('grade')
            ->add('id_aleatorio')
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->add('name')
            ->add('school_name')
            ->add('schedule')
            ->add('grade')
            ->add('id_aleatorio')
            ->getFormBuilder()
            ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
                $isEdit = $event->getData() !== null && $event->getData()->getId() > 0;
                
                $event->getForm()
                    ->add('file', FileType::class, array(
                        'label' => 'Archivo de Alumnos',
                        'required' => false,
                        'data_class' => null,
                        'help' => 'Archivos acceptables .csv',
                        'attr' => ['class' => 'form-control'],
                        'disabled' => $isEdit
                    ));
                
            })
            ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add('id')
            ->add('name')
            ->add('school_name')
            ->add('schedule')
            ->add('grade')
            ->add('id_aleatorio')
            ;
    }
}
