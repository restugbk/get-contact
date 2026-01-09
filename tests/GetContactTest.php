<?php

use PHPUnit\Framework\TestCase;
use Restugbk\GetContact;

class GetContactTest extends TestCase
{
    public function testInstanceCreation()
    {
        $gc = new GetContact("dummy-token", "0123456789abcdef0123456789abcdef");
        $this->assertInstanceOf(GetContact::class, $gc);
    }

    public function testValidateNumber()
    {
        $gc = new GetContact("dummy-token", "0123456789abcdef0123456789abcdef");
        $method = new ReflectionMethod(GetContact::class, 'checkNumber');
        $this->assertTrue($method->isPublic());
    }
}