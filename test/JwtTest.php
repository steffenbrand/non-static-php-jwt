<?php

declare(strict_types=1);

namespace SteffenBrand\NonStaticPhpJwt\Test;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\MethodProphecy;
use Prophecy\Prophecy\ObjectProphecy;
use SteffenBrand\NonStaticPhpJwt\Jwt;

class JwtTest extends TestCase
{
    /**
     * @var Jwt
     */
    private $jwt;

    protected function setUp()
    {
        parent::setUp();
        $this->jwt = $this->prophesize(Jwt::class);
    }

    public function getSut(): Dummy
    {
        return new Dummy($this->jwt->reveal());
    }

    public function testJwtEncodeCanBeProphecised(): void
    {
        $returnValue = 'string';
        $this->jwt->encode([], '')->shouldBeCalled()->willReturn($returnValue);

        $this->assertInstanceOf(ObjectProphecy::class, $this->jwt);
        $this->assertInstanceOf(MethodProphecy::class, $this->jwt->getMethodProphecies('encode')[0]);
        $this->assertEquals($returnValue, $this->getSut()->encode());
    }

    public function testJwtDecodeCanBeProphecised(): void
    {
        $returnValue = new \stdClass();
        $this->jwt->decode('', '')->shouldBeCalled()->willReturn($returnValue);

        $this->assertInstanceOf(ObjectProphecy::class, $this->jwt);
        $this->assertInstanceOf(MethodProphecy::class, $this->jwt->getMethodProphecies('decode')[0]);
        $this->assertEquals($returnValue, $this->getSut()->decode());
    }

    public function testJwtSignCanBeProphecised(): void
    {
        $returnValue = 'string';
        $this->jwt->sign('', '')->shouldBeCalled()->willReturn($returnValue);

        $this->assertInstanceOf(ObjectProphecy::class, $this->jwt);
        $this->assertInstanceOf(MethodProphecy::class, $this->jwt->getMethodProphecies('sign')[0]);
        $this->assertEquals($returnValue, $this->getSut()->sign());
    }
}