<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;

class AbstractEntityControllerTest extends KernelTestCase
{
    protected $controllerMock;

    public function setUp()
    {
        $kernel = $this->bootKernel();
        $this->controllerMock = new ControllerMock();
        $this->controllerMock->setContainer($kernel->getContainer());
    }

    public function testGetEntityRepository()
    {
        $this->controllerMock->setEntityClass(User::class);
        $output = $this->controllerMock->getEntityRepository();
        $this->assertInstanceOf(EntityRepository::class, $output, 'Output should be instance of EntityRepository');
    }

    public function testGetIdFromRequest()
    {
        $input = Request::create('products/1');
        $input->attributes->set('id', 1);
        $output = $this->controllerMock->getIdFromRequest($input);
        $this->assertEquals(1, $output, 'Output should be equal to 1');
    }
}
