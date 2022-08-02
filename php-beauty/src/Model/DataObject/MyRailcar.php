<?php

namespace App\Model\DataObject;

use Pimcore\Model\DataObject\Railcar;
use Pimcore\Bundle\EcommerceFrameworkBundle\Model\IndexableInterface;
use DynamicSearchBundle\Provider\PreConfiguredIndexProviderInterface;

class MyRailcar extends Railcar implements IndexableInterface
{
    /**
     * defines if product is included into the product index. If false, product doesn't appear in product index.
     *
     * @return bool
     */
    public function getOSDoIndexProduct(): bool
    {
        return $this->isPublished();
    }

    /**
     * defines the name of the price system for this product.
     * there should either be a attribute in pro product object or
     * it should be overwritten in mapped sub classes of product classes
     *
     * @return string
     */
    public function getPriceSystemName(): ?string
    {
        return 'default';
    }

    /**
     * returns if product is active.
     * there should either be a attribute in pro product object or
     * it should be overwritten in mapped sub classes of product classes in case of multiple criteria for product active state
     *
     * @param bool $inProductList
     *
     * @return bool
     */
    public function isActive(bool $inProductList = false): bool
    {
        return $this->isPublished();
    }

    /**
     * returns product type for product index (either object or variant).
     * by default it returns type of object, but it may be overwritten if necessary.
     *
     * @return string
     */
    public function getOSIndexType(): ?string
    {
        return $this->getType();
    }

    /**
     * returns parent id for product index.
     * by default it returns id of parent object, but it may be overwritten if necessary.
     *
     * @return int
     */
    public function getOSParentId()
    {
        return $this->getParentId();
    }
}
