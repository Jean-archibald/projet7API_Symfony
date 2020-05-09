<?php


namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpFoundation\JsonResponse;


class ExceptionSubscriber implements EventSubscriberInterface
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exceptionStatusCode = $event->getThrowable()->getStatusCode();
        if($exceptionStatusCode == 404) {
            $data = [
                'status' => $exceptionStatusCode,
                'message' => 'La page n\'existe pas.'
            ];

        }elseif($exceptionStatusCode == 403){
            $data = [
                'status' => $exceptionStatusCode,
                'message' => 'Votre statut ne vous permet d\'effectuer cette action'
            ];
        }elseif($exceptionStatusCode == 500){
            $data = [
                'status' => $exceptionStatusCode,
                'message' => 'Il y a un problème lié au serveur'
            ];
        }
        $response = new JsonResponse($data);
        $event->setResponse($response);
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.exception' => 'onKernelException',
        ];
    }

}