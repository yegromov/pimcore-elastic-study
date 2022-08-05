<?php

namespace App\Twig\Extension;

use App\Model\DataObject\ProductCategory;
use App\Website\LinkGenerator\CategoryLinkGenerator;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CategoryFilterExtension extends AbstractExtension
{
    /**
     * @var CategoryLinkGenerator
     */
    protected $categoryLinkGenerator;

    /**
     * CategoryFilterExtension constructor.
     *
     * @param CategoryLinkGenerator $categoryLinkGenerator
     */
    public function __construct(CategoryLinkGenerator $categoryLinkGenerator)
    {
        $this->categoryLinkGenerator = $categoryLinkGenerator;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('app_category_filter_generate_link', [$this, 'generateLink']),
            new TwigFunction('app_category_filter_prepare_data', [$this, 'prepareData']),
        ];
    }

    public function prepareData($currentValue, ProductCategory $rootCategory = null)
    {
        $data = new \stdClass();

        $data->parentCategories = [];
        $data->currentCategory = $this->getCurrentCategory($currentValue);
        //var_dump($data->currentCategory);
        if ($data->currentCategory) {
            $data->parentCategories = $data->currentCategory->getParentCategoryList($rootCategory);
        }

        $data->subCategories = $this->getSubCategories($data->currentCategory, $rootCategory);

        return $data;
    }

    /**
     * @param $currentValue
     *
     * @return ProductCategory
     */
    public function getCurrentCategory($currentValue)
    {
        return ProductCategory::getById($currentValue);
    }

    public function getSubCategories(ProductCategory $currentCategory = null, $rootCategory = null)
    {
        $subCategories = [];

        $parent = $currentCategory ?: $rootCategory;

        if ($parent) {
            $subCategories = array_filter($parent->getChildren(), function ($item) {
                return $item instanceof ProductCategory && $item->isPublished();
            });

//            foreach($currentCategory->getChildren() as $subCategory) {
//                $subCategories[] = $subCategory;
//            }
        } else {
        }

        return $subCategories;
    }

    /**
     * @param ProductCategory $category
     * @param ProductCategory|null $rootCategory
     * @param bool $reset
     *
     * @return string
     */
    public function generateLink(ProductCategory $category, ProductCategory $rootCategory = null, $reset = false): string
    {
        return $this->categoryLinkGenerator->generate($category, ['rootCategory' => $rootCategory], $reset);
    }
}
