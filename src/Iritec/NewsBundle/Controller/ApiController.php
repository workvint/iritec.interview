<?php

namespace Iritec\NewsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Iritec\NewsBundle\Entity\News;

class ApiController extends Controller
{
    /**
     * @Route("/api/news/{id}", requirements={"id": "\d+"})
     * @Method("GET")
     * @ParamConverter("item", class="IritecNewsBundle:News")
     */
    public function itemAction(News $item=null)
    {       
        return new JsonResponse($item, $item ? 200 : 404);
    }
    
    /**
     * @Route("/api/news")
     * @Method("GET")
     */
    public function listAction(Request $request)
    {
        $sort = $request->query->getAlpha('sort', 'date');
        $order = $request->query->getAlpha('order', 'asc');
        
        if (!in_array($sort, array('date', 'title'))) {
            $sort = 'date';
        }
        
        if (!in_array($order, array('asc', 'desc'))) {
            $order = 'asc';
        }
        
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 5);
        
        if ($page < 1) {
            $page = 1;
        }
        
        if ($limit < 1) {
            $limit = 5;
        } elseif ($limit > 100) {
            $limit = 100;
        }
        
        $items = $this->getDoctrine()->getRepository('IritecNewsBundle:News')
            ->findAllByPaginator($page, $limit, $sort, $order);
        
        return new JsonResponse($items);
    }   
}
