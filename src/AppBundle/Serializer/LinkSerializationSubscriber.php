<?php
namespace AppBundle\Serializer;

use JMS\Serializer\EventDispatcher\EventSubscriberInterface;

class LinkSerializationSubscriber implements EventSubscriberInterface{
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
