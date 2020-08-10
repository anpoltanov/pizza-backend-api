<?php

declare(strict_types=1);

namespace App\Listener;

use App\Listener\ResponseFactory\JsonResponseFactory;
use App\Listener\ResponseFactory\ResponseFactoryInterface;
use App\Serializer\JsonSerializer;
use App\Serializer\SerializerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class ResponseSerializerListener
 * @package App\Listener
 */
class JsonResponseSerializerListener implements EventSubscriberInterface
{
    /** @var SerializerInterface */
    protected $serializer;
    /** @var ResponseFactoryInterface */
    protected $responseFactory;

    /**
     * JsonResponseSerializerListener constructor.
     * @param JsonSerializer $jsonSerializer
     * @param JsonResponseFactory $jsonResponseFactory
     */
    public function __construct(
        JsonSerializer $jsonSerializer,
        JsonResponseFactory $jsonResponseFactory
    ) {
        $this->serializer = $jsonSerializer;
        $this->responseFactory = $jsonResponseFactory;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => [
                ['onKernelView', 0]
            ],
        ];
    }

    /**
     * @param ViewEvent $event
     */
    public function onKernelView(ViewEvent $event)
    {
        $result = $event->getControllerResult();
        if (!is_array($result)) {
            throw new \InvalidArgumentException('Array expected as a result of controller');
        }
        $ttl = !empty($result['ttl']) ? $result['ttl'] : 0;
        $code = !empty($result['status_code']) ? $result['status_code'] : Response::HTTP_OK;
        $data = !empty($result['data']) ? $result['data'] : null;
        $responseString = $this->serializer->serialize($data);
        $response = $this->responseFactory->create($responseString);
        $response->setTtl($ttl)->setStatusCode($code);
        $event->setResponse($response);
    }
}