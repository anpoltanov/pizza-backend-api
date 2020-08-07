<?php

declare(strict_types=1);

namespace App\Controller\Strategy;

use Symfony\Component\HttpFoundation\Request;

/**
 * Interface RequestStrategyInterface
 * @package App\Controller\Strategy
 */
interface RequestStrategyInterface
{
    /**
     * @param Request $request
     * @return array
     */
    public function unserializeRequestContent(Request $request): array;
}