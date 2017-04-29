<?php
namespace AppBundle\Pagination;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

class PaginationFactory {
  public function createCollection(QueryBuilder $qb, Request $request, $route, array $routeParams){
    $page = $request->query->get('page', 1);
    
    
    
    $adapter = new DoctrineORMAdapter($qb);
    $pagerfanta = new Pagerfanta($adapter);
    $pagerfanta->setMaxPerPage(10);
    $pagerfanta->setCurrentPage($page);
    
    $programmers = array();
    foreach ($pagerfanta->getCurrentPageResults() as $programmer){
      $programmers[] = $programmer;
    };

    $paginatedCollection = new PaginatedCollection(
      $programmers, 
      $pagerfanta->getNbResults()
    );
    
    $createLinkUrl = function($targetPage) use ($route, $routeParams){
      return $this->generateUrl($route, array_merge(
        $routeParams,
        array('page' => $targetPage)
      ));
    };
    
    $paginatedCollection->addLink('self', $createLinkUrl($page));
    $paginatedCollection->addLink('first', $createLinkUrl(1));
    $paginatedCollection->addLink('last', $createLinkUrl($pagerfanta->getNbPages()));
    
    if ($pagerfanta->hasNextPage()){
      $paginatedCollection->addLink('next', $createLinkUrl($pagerfanta->getNextPage()));
    }
    
    if ($pagerfanta->hasPreviousPage()){
      $paginatedCollection->addLink('prev', $createLinkUrl($pagerfanta->getPreviousPage()));
    } 
  }
}
