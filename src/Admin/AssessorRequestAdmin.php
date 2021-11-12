<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;

class AssessorRequestAdmin extends AbstractAdmin
{
    protected $datagridValues = [
        '_page' => 1,
        '_per_page' => 32,
        '_sort_order' => 'ASC',
        '_sort_by' => 'name',
    ];

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        $collection->clearExcept('list');
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('firstName', null, [
                'label' => 'Prénom',
            ])
            ->add('lastName', null, [
                'label' => 'Nom',
            ])
            ->add('emailAddress', null, [
                'label' => 'Email',
            ])
            ->add('birthCity', null, [
                'label' => 'Ville de naissance',
            ])
            ->add('voteCity', null, [
                'label' => 'Ville d\'inscription',
            ])
            ->add('officeNumber', null, [
                'label' => 'Numéro bureau d\'inscription',
            ])
            ->add('assessorCity', null, [
                'label' => 'Ville souhaitée',
            ])
            ->add('office', null, [
                'label' => 'Fonction souhaitée',
            ])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('firstName', null, [
                'label' => 'Prénom',
            ])
            ->add('lastName', null, [
                'label' => 'Nom',
            ])
            ->add('emailAddress', null, [
                'label' => 'Email',
            ])
        ;
    }
}
