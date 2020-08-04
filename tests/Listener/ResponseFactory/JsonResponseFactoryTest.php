<?php

namespace App\Tests\Listener\ResponseFactory;

use App\Listener\ResponseFactory\JsonResponseFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

class JsonResponseFactoryTest extends TestCase
{
    public function testCreate()
    {
        $input = '[{"id":1,"name":"Quattro Formaggi","description":"Traditional pizza with four sorts of cheese","imageUrl":null,"priceEUR":9.99,"priceUSD":8.99}]';
        $output = JsonResponseFactory::create($input);
        $this->assertInstanceOf(JsonResponse::class, $output);
        $this->assertEquals($input, $output->getContent());
    }
}
