<?php


namespace App\EventListener;


use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Products;
use App\Entity\Partners;
use App\Entity\Clients;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\SerializerInterface;

class JsonResponseSubscriber implements EventSubscriberInterface
{

    private $serializer;

    public function __construct( SerializerInterface $serializer)
    {
        $this->serializer = $serializer;

    }

    public static function getSubscribedEvents()
        {
            return [
                KernelEvents::VIEW => ['notifyCreation', EventPriorities::POST_WRITE],
            ];
        }

    public function notifyCreation(ViewEvent $event) : void
    {
        $ressource = $event->getControllerResult();
        $path = $event->getRequest()->getPathInfo();
        $method = $event->getRequest()->getMethod();
        $ressourceSerialize = $this->serializer->serialize($ressource, 'json');
        $data = ['ressource' => $ressourceSerialize];



        if (($ressource instanceof Products || $ressource instanceof Partners || $ressource instanceof Clients )
            && ($path === "/api/products" || $path === "/api/partners"|| $path === "/api/clients")
            && (Request::METHOD_POST === $method)) {
                $response = new JsonResponse($data, 201);
                $event->setResponse($response);
            }

        if (($ressource instanceof Products || $ressource instanceof Partners || $ressource instanceof Clients )
            && (1 === preg_match('#/api/products/([0-9]+)#', $path , $params)
                || 1 === preg_match('#/api/partners/([0-9]+)#', $path , $params)
                || 1 === preg_match('#/api/clients/([0-9]+)#', $path , $params))) {
            if (Request::METHOD_PUT === $method) {
                $response = new JsonResponse($data, 200);
                $event->setResponse($response);
            }
        }

    }


}
