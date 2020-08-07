<?php

declare(strict_types=1);

namespace App\Controller\Strategy;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class JsonRequestStrategy
 * @package App\Controller\Traits
 */
class JsonRequestStrategy implements RequestStrategyInterface
{
    /**
     * @param Request $request
     * @return array
     */
    public function unserializeRequestContent(Request $request): array
    {
        $content = $request->getContent();
        if (empty($content)) {
            return [];
        }

        if (!is_string($content) || !$request->getContentType() === 'json') {
            throw new BadRequestHttpException('Malformed JSON');
        }
        try {
            $content = json_decode($content, true);
        } catch (\Exception $e) {
            throw new BadRequestHttpException('Malformed JSON');
        }

        if ($content === null) {
            throw new BadRequestHttpException('Malformed JSON');
        }

        return $content;
    }
}