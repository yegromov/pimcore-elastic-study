<?php

namespace App\Controller;

use Pimcore\Bundle\EcommerceFrameworkBundle\FilterService\ListHelper;
use Pimcore\Bundle\EcommerceFrameworkBundle\IndexService\ProductList\ProductListInterface;
use Pimcore\Controller\FrontendController;
use Pimcore\Bundle\EcommerceFrameworkBundle\Factory;
use Pimcore\Model\DataObject\FilterDefinition;
use Pimcore\Model\DataObject;
#use Pimcore\Model\DataObject\ProductCategory;
use App\Model\DataObject\ProductCategory;
use Pimcore\Config;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\SlidingPagination;

class RailcarController extends FrontendController
{
    /**
     * @Template
     *
     * @Route("/es-railcars")
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function railcarsListAction(Request $request, PaginatorInterface $paginator)
    {
        $searchValue = $request->request->get('searchValue');
        /*
        $ecommerceFactory = \Pimcore\Bundle\EcommerceFrameworkBundle\Factory::getInstance();

        $templateParams = [];
        $params = array_merge($request->query->all(), $request->attributes->all());

        $indexService = $ecommerceFactory->getIndexService();
        //$productListing = $indexService->getProductListForCurrentTenant();
        $productListing = $indexService->getProductListForTenant('EsTenant');
        $templateParams['productListing'] = $productListing;

        
        if ($request->get('filterdefinition') instanceof FilterDefinition) {
            $filterDefinition = $request->get('filterdefinition');
        }

        if (empty($filterDefinition)) {
            $filterDefinition = DataObject::getByPath("/ProductManagement/Filter-Definitions/NameSearchFilter");
        }

        // create and init filter service
        $filterService = $ecommerceFactory->getFilterService();
        (new \Pimcore\Bundle\EcommerceFrameworkBundle\FilterService\ListHelper)->setupProductList($filterDefinition, $productListing, $params, $filterService, true, true);
        $templateParams['filterService'] = $filterService;
        $templateParams['filterDefinition'] = $filterDefinition;

        // inject and use Knp Paginator service: PaginatorInterface $paginator
        $paginator = $paginator->paginate(
            $productListing,
            $request->get('page', 1),
            18
        );
        $templateParams['results'] = $paginator;
        $templateParams['paginationVariables'] = $paginator->getPaginationData();

        //return $this->render('Path/template.html.twig', $templateParams);
        return $templateParams;
    */

        //---------------------------

        $productList = Factory::getInstance()->getIndexService()->getProductListForTenant('EsTenant');

        foreach ($productList as $railcar) {
            $products[] = [
                'id' => $railcar->getId(),
                'model' => $railcar->getModel(),
                'name' => $railcar->getCar_name(),
                'factory' => stripslashes(stripslashes($railcar->getFactory()))
            ];
        }

        return [
            'railcars' => $products,
            'searchValue' => $searchValue,
        ];
    }

    /**
     * @Route("/mysql-railcars")
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function mysqlRailcarsListAction()
    {
        $productList = Factory::getInstance()->getIndexService()->getProductListForTenant('MySQLTenant');

        foreach ($productList as $railcar) {
            $products[] = [
                'id' => $railcar->getId(),
                'name' => $railcar->getCar_name(),
                'factory' => stripslashes(stripslashes($railcar->getFactory()))
            ];
        }

        return $this->json($products);
    }

    /**
     * @Route("/shop/{path}{categoryname}~c{category}", name="shop-category", defaults={"path"=""}, requirements={"path"=".*?", "categoryname"="[\w-]+", "category"="\d+"})
     *
     * @param Request $request
     * @param Factory $ecommerceFactory
     * @param SegmentTrackingHelperService $segmentTrackingHelperService
     * @param ListHelper $listHelper
     *
     * @return Response
     */

    public function esRailcarsListAction(Request $request, Factory $ecommerceFactory, PaginatorInterface $paginator, ListHelper $listHelper)
    {
        $params = array_merge($request->query->all(), $request->attributes->all());

        $params['parentCategoryIds'] = $params['category'] ?? null;

        $category = ProductCategory::getById($params['category'] ?? null);
        $params['category'] = $category;

        $indexService = $ecommerceFactory->getIndexService();
        $productListing = $indexService->getProductListForTenant('EsTenant');
        //$productListing->setVariantMode(ProductListInterface::VARIANT_MODE_VARIANTS_ONLY);
        $params['productListing'] = $productListing;

        // Current filter loading
        if ($category) {
            $filterDefinition = $category->getFilterDefinition();

            //TODO We can track segments for personalization on this step after
        }

        if ($request->get('filterdefinition') instanceof FilterDefinition) {
            $filterDefinition = $request->get('filterdefinition');
        }

        // It seems that this made for make sure that we have filter definition
        if (empty($filterDefinition)) {
            $filterDefinition = Config::getWebsiteConfig()->get('fallbackFilterdefinition');
        }

        $filterService = $ecommerceFactory->getFilterService();
        $listHelper->setupProductList($filterDefinition, $productListing, $params, $filterService, true);
        $params['filterService'] = $filterService;
        $params['filterDefinition'] = $filterDefinition;

        $paginator = $paginator->paginate(
            $productListing,
            $request->get('page', 1),
            100
        );

        $params['results'] = $paginator;

        return $this->render('railcar/es-railcar-list.html.twig', $params);
    }

    /**
     * @Route("/update-railcars")
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function updateRailcarsAction()
    {        
        $entries = new \Pimcore\Model\DataObject\Railcar\Listing();
        $entries->setCondition("car_name LIKE ?", ["%бункер%"]);
        $category = \Pimcore\Model\DataObject\ProductCategory::getById(4070);

        $products = [];
        foreach ($entries as $entry) {
            //$factoryName = stripslashes($entry->getFactory());
            $entry->setCategories([ $category ]);
            $entry->save(["versionNote" => "Setting categories"]);
            $products[] = $entry->getCar_name();       
        }
        
        return $this->json($products);
    }
}
