<?php

declare(strict_types=1);

namespace SteffenBrand\NonStaticPhpJwt\Test;

use SteffenBrand\NonStaticPhpJwt\Jwt;

class Dummy
{
    /**
     * @var Jwt
     */
    private $jwt;

    /**
     * Dummy constructor.
     * @param Jwt $jwt
     */
    public function __construct(Jwt $jwt)
    {
        $this->jwt = $jwt;
    }

    public function encode()
    {
        return $this->jwt->encode([], '');
    }

    public function decode()
    {
        return $this->jwt->decode('', '');
    }

    public function sign()
    {
        return $this->jwt->sign('', '');
    }
}