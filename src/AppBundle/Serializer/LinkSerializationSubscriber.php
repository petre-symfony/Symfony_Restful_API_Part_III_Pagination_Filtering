<?php
namespace AppBundle\Serializer;

use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\JsonSerializationVisitor;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Routing\RouterInterface;
use AppBundle\Entity\Programmer;
use AppBundle\Annotation\Link;

class LinkSerializationSubscriber implements EventSubscriberInterface{
  private $router;
  private $annotationReader;
  
  public function __construct(RouterInterface $router, Reader $annotationReader) {
    $this->router = $router;
    $this->annotationReader = $annotationReader;
  }

    public function onPostSerialize(ObjectEvent $event){
    /** @var JsonSerializationVisitor $visitor */
    $visitor = $event->getVisitor(); 
    
    $object = $event->getObject();
    $annotations = $this->annotationReader
      ->getClassAnnotations(new \ReflectionObject($object));
    $links = array();
    
    foreach ($annotations as $annotation){
      if ($annotation instanceof Link){
        $uri = $this->router->generate(
          $annotation->route, 
          $annotation->params
        );
        
        $links[$annotation->name] = $uri;
      }
    }
    
    $visitor->addData('_links', $links);
  }


  public static function getSubscribedEvents() {
    return array(
      array(
        'event'  => 'serializer.post_serialize',
        'method' => 'onPostSerialize',
        'format' => 'json',
        'class'  => 'AppBundle\Entity\Programmer'
      )    
    );
  }
}
