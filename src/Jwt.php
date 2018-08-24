<?php

declare(strict_types=1);

namespace SteffenBrand\NonStaticPhpJwt;

use DomainException;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use UnexpectedValueException;

/**
 * Non-static wrapper for firebird/php-jwt.
 * @package SteffenBrand\NonStaticPhpJwt
 */
class Jwt
{
    /**
     * Decodes a JWT string into a PHP object.
     *
     * @param string            $jwt            The JWT
     * @param string|array      $key            The key, or map of keys.
     *                                          If the algorithm used is asymmetric, this is the public key
     * @param array             $allowed_algs   List of supported verification algorithms
     *                                          Supported algorithms are 'HS256', 'HS384', 'HS512' and 'RS256'
     * @param int               $leeway         You can add a leeway to account for when there is a clock skew times between
     *                                          the signing and verifying servers. It is recommended that this leeway should
     *                                          not be bigger than a few minutes.
     *
     * @return \stdClass                        The JWT's payload as a PHP object
     *
     * @throws UnexpectedValueException         Provided JWT was invalid
     * @throws SignatureInvalidException        Provided JWT was invalid because the signature verification failed
     * @throws BeforeValidException             Provided JWT is trying to be used before it's eligible as defined by 'nbf'
     * @throws BeforeValidException             Provided JWT is trying to be used before it's been created as defined by 'iat'
     * @throws ExpiredException                 Provided JWT has since expired, as defined by the 'exp' claim
     * @throws DomainException
     */
    public function decode(
        string $jwt,
        $key,
        array $allowed_algs = [],
        int $leeway = 0
    ) : \stdClass {
        \Firebase\JWT\JWT::$leeway = $leeway;
        return \Firebase\JWT\JWT::decode($jwt, $key, $allowed_algs);
    }

    /**
     * Converts and signs a PHP object or array into a JWT string.
     *
     * @param \stdClass|array   $payload        PHP object or array
     * @param string            $key            The secret key.
     *                                          If the algorithm used is asymmetric, this is the private key
     * @param string            $alg            The signing algorithm.
     *                                          Supported algorithms are 'HS256', 'HS384', 'HS512' and 'RS256'
     * @param mixed             $keyId
     * @param array             $head           An array with header elements to attach
     *
     * @return string                           A signed JWT
     *
     * @throws DomainException Unsupported algorithm was specified
     */
    public function encode(
        $payload, string $key,
        string $alg = 'HS256',
        ?string $keyId = null,
        ?array $head = null
    ) : string {
        return \Firebase\JWT\JWT::encode($payload, $key, $alg, $keyId, $head);
    }

    /**
     * Sign a string with a given key and algorithm.
     *
     * @param string            $msg            The message to sign
     * @param string|resource   $key            The secret key
     * @param string            $alg            The signing algorithm.
     *                                          Supported algorithms are 'HS256', 'HS384', 'HS512' and 'RS256'
     *
     * @return string                           An encrypted message
     *
     * @throws DomainException Unsupported algorithm was specified
     * @throws DomainException OpenSSL unable to sign data
     */
    public function sign(
        string $msg,
        $key,
        string $alg = 'HS256'
    ) : string {
        return \Firebase\JWT\JWT::sign($msg, $key, $alg);
    }
}