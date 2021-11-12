<?php

namespace App\Admin;

use App\Newsletter\Events;
use App\Newsletter\NewsletterEvent;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class NewsletterSubscriptionAdmin extends AbstractAdmin
{
    protected $datagridValues = [
        '_page' => 1,
        '_per_page' => 128,
        '_sort_order' => 'DESC',
        '_sort_by' => 'createdAt',
    ];

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->add('email', null, [
                'label' => 'Adresse e-mail',
            ])
            ->add('postalCode', TextType::class, [
                'label' => 'Code postal',
            ])
        ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add('email', null, [
                'label' => 'Adresse e-mail',
            ])
            ->add('postalCode', null, [
                'label' => 'Code postal',
            ])
            ->add('createdAt', null, [
                'label' => 'Date de création',
            ])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('email', null, [
                'label' => 'Adresse e-mail',
                'show_filter' => true,
            ])
            ->add('postalCode', null, [
                'label' => 'Code postal',
            ])
        ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->addIdentifier('email', null, [
                'label' => 'Adresse e-mail',
            ])
            ->add('postalCode', null, [
                'label' => 'Code postal',
            ])
            ->add('createdAt', null, [
                'label' => 'Date de création',
            ])
            ->add('_action', null, [
                'virtual_field' => true,
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ])
        ;
    }

    public function postRemove(object $object): void
    {
        $this->eventDispatcher->dispatch(new NewsletterEvent($object), Events::UNSUBSCRIBE);
    }

    public function postUpdate(object $object): void
    {
        $this->eventDispatcher->dispatch(new NewsletterEvent($object), Events::UPDATE);
    }

    /**
     * @required
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher): void
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        $collection->remove('create');
    }
}
