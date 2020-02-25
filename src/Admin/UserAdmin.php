<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

final class UserAdmin extends AbstractAdmin
{

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('id')
            ->add('email')
            ->add('password')
            ->add('account.name')
            ->add('roles');
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('id')
            ->add('email')
            ->add('password')
            ->add('account.name')
            ->add('roles')
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
        $container = $this->getConfigurationPool()->getContainer();
        $roles = $container->getParameter('security.role_hierarchy.roles');
        $roleChoices = $this->flattenRoles($roles);

        $formMapper
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class)
            ->add(
                'account',
                ModelAutocompleteType::class,
                [
                    'property' => 'name',
                    'to_string_callback' => function ($entity, $property) {
                        return $entity->getName();
                    },
                    'minimum_input_length' => 1
                ]
            )
            ->add('roles', ChoiceType::class, array(
                'choices' => $roleChoices,
                'multiple' => true
            ));
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add('id')
            ->add('email')
            ->add('password')
            ->add('account.name')
            ->add('roles');
    }

    // Encode password when creating users from Sonata Admin.
    public function prePersist($object)
    {
        $plainPassword = $object->getPassword();
        $container = $this->getConfigurationPool()->getContainer();
        $encoder = $container->get('security.password_encoder');
        $encoded = $encoder->encodePassword($object, $plainPassword);
        $object->setPassword($encoded);
    }

    private function flattenRoles($roleHierachy)
    {
        $flatRoles = array();
        foreach ($roleHierachy as $key => $roles) {

            if (empty($roles)) {
                continue;
            }

            // Returns the root roles from role_hierarchy (e.g. ROLE_ADMIN)
            $flatRoles[$key] = $key;

            // Returns sub roles of each root role from role_hierarchy (e.g. ROLE_SONATA_ADMIN which is under ROLE_ADMIN)
            // foreach ($roles as $role) {
            //     if (!isset($flatRoles[$role])) {
            //         $flatRoles[$role] = $role;
            //     }
            // }
        }

        return $flatRoles;
    }
}
