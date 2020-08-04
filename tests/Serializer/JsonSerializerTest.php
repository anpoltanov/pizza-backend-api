<?php

declare(strict_types=1);

namespace App\Tests\Serializer;

use App\Serializer\JsonSerializer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class JsonSerializerTest extends TestCase
{
    protected $serializer;

    public function setUp(): void
    {
        $this->serializer = new JsonSerializer();
    }

    protected function tearDown(): void
    {
        unset($this->serializer);
    }

    public function testSerialize() {
        $input = [
            'ttl' => 10,
            'status_code' => Response::HTTP_OK,
            'data' => [
                'Пример данных',
            ],
        ];
        $output = $this->serializer->serialize($input);
        $this->assertIsString($output, 'Output must be string');
        $this->assertEquals(
            '{"ttl":10,"status_code":200,"data":["Пример данных"]}',
            $output,
            'Incorrect json serializer output'
        );
    }
}