<?php

namespace App\Algolia\Sonata\Model;

use Algolia\SearchBundle\SearchService;
use App\Algolia\Query\QueryBuilder;
use App\Algolia\Sonata\ProxyQuery\ProxyQuery;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\AdminBundle\FieldDescription\FieldDescriptionInterface;
use Sonata\AdminBundle\Model\ModelManagerInterface;

class ModelManager implements ModelManagerInterface
{
    private $algolia;
    private $queryBuilder;

    public function __construct(SearchService $algolia, QueryBuilder $queryBuilder)
    {
        $this->algolia = $algolia;
        $this->queryBuilder = $queryBuilder;
    }

    public function createQuery(string $class): ProxyQueryInterface
    {
        return new ProxyQuery($this->algolia, $this->queryBuilder, $class);
    }

    public function getModelIdentifier($class)
    {
        return [];
    }

    public function getDefaultSortValues($class)
    {
        return [
            '_page' => 1,
            '_per_page' => 25,
        ];
    }

    public function getNewFieldDescriptionInstance($class, $name, array $options = [])
    {
        $fieldDescription = new FieldDescription();
        $fieldDescription->setName($name);
        $fieldDescription->setOptions($options);

        return $fieldDescription;
    }

    public function hasMetadata(string $class): bool
    {
        return false;
    }

    public function getPaginationParameters(DatagridInterface $datagrid, $page)
    {
        $values = $datagrid->getValues();

        $values['_page'] = $page;

        return ['filter' => $values];
    }

    public function create($object): void
    {
    }

    public function update($object): void
    {
    }

    public function delete($object): void
    {
    }

    public function findBy(string $class, array $criteria = []): array
    {
        return [];
    }

    public function findOneBy(string $class, array $criteria = []): ?object
    {
    }

    public function find(string $class, $id): ?object
    {
    }

    public function batchDelete(string $class, ProxyQueryInterface $queryProxy): void
    {
    }

    public function getParentFieldDescription($parentAssociationMapping, $class)
    {
    }

    public function getIdentifierValues(object $model): array
    {
        return [];
    }

    public function getIdentifierFieldNames(string $class): array
    {
        return [];
    }

    public function getNormalizedIdentifier(object $model): ?string
    {
        return null;
    }

    public function getUrlsafeIdentifier(object $model): ?string
    {
    }

    public function getModelInstance()
    {
        return null;
    }

    public function getModelCollectionInstance()
    {
        return null;
    }

    public function collectionRemoveElement()
    {
    }

    public function collectionAddElement()
    {
    }

    public function collectionHasElement()
    {
        return false;
    }

    public function collectionClear(&$collection)
    {
    }

    public function getSortParameters(FieldDescriptionInterface $fieldDescription)
    {
        return [];
    }

    public function modelReverseTransform()
    {
        return null;
    }

    public function modelTransform($class, $instance)
    {
    }

    public function executeQuery($query)
    {
    }

    public function getDataSourceIterator(
        DatagridInterface $datagrid,
        array $fields,
        $firstResult = null,
        $maxResult = null
    ) {
        return null;
    }

    public function getExportFields(string $class): array
    {
        return [];
    }

    public function addIdentifiersToQuery(string $class, ProxyQueryInterface $query, array $idx): void
    {
    }

    public function reverseTransform(object $object, array $array = []): void
    {
    }

    public function supportsQuery(object $query): bool
    {
        return false;
    }
}
