<?php

namespace App\Admin;

use App\Algolia\Sonata\Builder\DatagridBuilder;
use App\Algolia\Sonata\Model\ModelManager;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Route\RouteCollectionInterface;

class AbstractAlgoliaAdmin extends AbstractAdmin
{
    protected function configureFilterParameters(array $parameters): array
    {
        unset($parameters['_sort_by'], $parameters['_sort_order']);

        return $parameters;
    }

    protected function configureBatchActions(array $actions): array
    {
        return [];
    }

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        $collection->clearExcept('list');
    }

    /** @required */
    final public function setAlgoliaManager(ModelManager $modelManager): void
    {
        parent::setModelManager($modelManager);
    }

    /** @required */
    final public function setAlgoliaDatagridBuilder(DatagridBuilder $datagridBuilder): void
    {
        parent::setDatagridBuilder($datagridBuilder);
    }
}
