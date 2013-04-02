<?php

namespace AJT\Asana\Plugin;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ConvertPostFieldToBodyPlugin implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            'request.before_send' => 'onBeforeSend'
        );
    }

    public function onBeforeSend(\Guzzle\Common\Event $event)
    {
        $request = $event['request'];
 
        // Move postfields to body and set content-length header
        $request->addHeader("Content-Length",strlen($request->getPostFields()->__toString()));
        $request->setBody($request->getPostFields()->__toString());

        // Remove postfields from request
        foreach($request->getPostFields() as $field){
            $request->removePostField($field);    
        }
 
        //echo 'About to send a request: ' . $request . "\n";
    }
}