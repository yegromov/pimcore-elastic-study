<?php

namespace App\Controller;

use Knp\Component\Pager\PaginatorInterface;
use Pimcore\Controller\FrontendController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Pimcore\Bundle\EcommerceFrameworkBundle\Factory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pimcore\Model\DataObject;
use Pimcore\Model\DataObject\FilterDefinition;
use Pimcore\Model\DataObject\ProductCategory;
use Pimcore\Config;

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
}
