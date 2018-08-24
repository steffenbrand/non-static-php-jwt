# non-static-php-jwt
non-static-php-jwt is a wrapper for [firebase/php-jwt](https://github.com/firebase/php-jwt) to make it easily mockable
with [phpspec/prophecy](https://github.com/phpspec/prophecy) (or any other mocking library) within your phpunit tests.

## Installation
```
composer require steffenbrand/non-static-php-jwt
```

## Versioning
The releases will match the release versions of [firebase/php-jwt](https://github.com/firebase/php-jwt) starting with ^5.0.  
The supported PHP versions will be ^7.1, since return types and type hinting are used in this library.

## Usage
It's just a wrapper for [firebase/php-jwt](https://github.com/firebase/php-jwt), so the usage is almost the same, except the fact that you have to create an instance of `\SteffenBrand\NonStaticPhpJwt\Jwt` first.

### Encoding and decoding
```php
$jwt = new \SteffenBrand\NonStaticPhpJwt\Jwt();

$key = 'example_key';
$token = [
    'iss' => 'http://example.org',
    'aud' => 'http://example.com',
    'iat' => 1356999524,
    'nbf' => 1357000000
];

$webToken = $jwt->encode($token, $key);
$decoded = $jwt->decode($webToken, $key, ['HS256']);

var_dump($decoded);
print_r((array) $decoded);
```

### Adding a leeway
You can add a leeway to account for when there is a clock skew times between
the signing and verifying servers. It is recommended that this leeway should
not be bigger than a few minutes.

Source: http://self-issued.info/docs/draft-ietf-oauth-json-web-token.html#nbfDef

The leeway if the fourth parameter of the decode method and defaults to `0`.
```php
$jwt->decode($jwt, $key, ['HS256'], $leeway = 60);
```

### Prophecising
The primary goal of this library is to allow prophecising the results of JWT methods within you phpunit tests.

```php
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
```