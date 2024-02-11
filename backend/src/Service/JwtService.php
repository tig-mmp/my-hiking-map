<?php

namespace App\Service;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class JwtService
{
    private $privateKey;
    private $publicKey;

    public function __construct(string $passPhrase, ParameterBagInterface $parameterBag)
    {
        $projectDir = $parameterBag->get("kernel.project_dir");
        $this->privateKey = openssl_pkey_get_private(file_get_contents("$projectDir/config/jwt/private.pem"), $passPhrase);
        $this->publicKey = file_get_contents("$projectDir/config/jwt/public.pem");
    }

    public function generateToken(array $data, int $expiration = 60 * 60 * 24): string
    {
        $issuedAt = time();
        $expire = $issuedAt + $expiration;

        $payload = [
            "iat" => $issuedAt,
            "exp" => $expire,
            "data" => $data,
        ];

        return JWT::encode($payload, $this->privateKey, "RS256");
    }

    public function decodeToken(?string $jwt)
    {
        if (!$jwt) {
            throw new HttpException(Response::HTTP_FORBIDDEN, "unauthenticated");
        }
        $jwt = explode("Bearer ", $jwt)[1];
        try {
            return JWT::decode($jwt, new Key($this->publicKey, "RS256"));
        } catch (Exception $e) {
            throw new HttpException(Response::HTTP_FORBIDDEN, $e->getMessage());
        }
    }
}
