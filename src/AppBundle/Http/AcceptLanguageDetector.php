<?php

namespace AppBundle\Http;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class AcceptLanguageDetector implements EventSubscriberInterface
{
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $request = $event->getRequest();
        $request->attributes->set('_locale', $request->getPreferredLanguage([ 'fr', 'en' ]));
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [ 'onKernelRequest', 150 ],
        ];
    }
}
