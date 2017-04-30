<?php
namespace AppBundle\Serializer;

use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;

class LinkSerializationSubscriber implements EventSubscriberInterface{
  public function onPostSerialize(ObjectEvent $event){
    
  }


  public function getSubscribedEvents() {
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
