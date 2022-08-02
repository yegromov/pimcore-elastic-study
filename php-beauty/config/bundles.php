<?php

//return [
//Twig\Extra\TwigExtraBundle\TwigExtraBundle::class => ['all' => true],
//];

return [
    Pimcore\Bundle\DataHubBundle\PimcoreDataHubBundle::class => [
        "enabled" => TRUE,
        "priority" => 15,
        "environments" => []
    ],
    Pimcore\Bundle\EcommerceFrameworkBundle\PimcoreEcommerceFrameworkBundle::class => TRUE,
    Pimcore\Bundle\DataImporterBundle\PimcoreDataImporterBundle::class => TRUE,
    \DsTrinityDataBundle\DsTrinityDataBundle::class => ['all' => true],
    \DsElasticSearchBundle\DsElasticSearchBundle::class => ['all' => true],
];
