<?php

namespace App\DynamicSearch\IndexDefinition\Trinity;

use DynamicSearchBundle\Document\Definition\DocumentDefinitionBuilderInterface;
use DynamicSearchBundle\Document\Definition\DocumentDefinitionInterface;
use DynamicSearchBundle\Document\Definition\DocumentDefinitionContextBuilderInterface;
use DynamicSearchBundle\Document\IndexDocument;
use DynamicSearchBundle\Normalizer\Resource\ResourceMetaInterface;
use DynamicSearchBundle\Provider\PreConfiguredIndexProviderInterface;

class Definition implements
    DocumentDefinitionBuilderInterface,
    DocumentDefinitionContextBuilderInterface,
    PreConfiguredIndexProviderInterface
{
    public function preConfigureIndex(IndexDocument $indexDocument): void
    {
        //var_dump($indexDocument);  
    }   

    public function isApplicable(string $contextName, ResourceMetaInterface $resourceMeta): bool
    {
        if ($resourceMeta->getResourceCollectionType() !== 'object') {
            return false;
        }

        return true;
    }

    public function isApplicableForContext(string $contextName): bool
    {
        //var_dump($contextName);
        return true;
    }

    public function buildDefinition(DocumentDefinitionInterface $definition, array $normalizerOptions): DocumentDefinitionInterface
    {
        //var_dump($definition);

        $definition
            ->addSimpleDocumentFieldDefinition([
                'name'              => 'id',
                'index_transformer' => [
                    'type' => 'keyword',
                ],
                'data_transformer'  => [
                    'type'          => 'element_id_extractor'
                ]
            ])
            ->addSimpleDocumentFieldDefinition([
                'name'              => 'car_name',
                'index_transformer' => [
                    'type' => 'text',
                ],
                'data_transformer'  => [
                    'type'          => 'object_getter_extractor',
                    'configuration' => ['method' => 'getName']
                ]
            ])
            ->addPreProcessFieldDefinition([
                'type'          => 'object_relations_getter_extractor',
                'configuration' => [
                    'relations' => 'categories',
                    'method'    => 'getId',
                ]
            ], function (DocumentDefinitionInterface $definition, array $preProcessedTransformedData) {
                foreach ($preProcessedTransformedData as $categoryId) {
                    $definition->addSimpleDocumentFieldDefinition([
                        'name'              => sprintf('category_%d', $categoryId),
                        'index_transformer' => [
                            'type' => 'keyword',
                        ],
                        'data_transformer'  => [
                            'type'          => 'normalizer_value_callback',
                            'configuration' => ['value' => '1']
                        ]
                    ]);
                }
            });

        return $definition;
    }
}
