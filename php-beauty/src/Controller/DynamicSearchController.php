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

class DynamicSearchController extends FrontendController
{
    /**
     * @Template
     *
     * @Route("/dyn-railcars")
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function railcarsListAction(Request $request, PaginatorInterface $paginator)
    {
 
        
    }
}
