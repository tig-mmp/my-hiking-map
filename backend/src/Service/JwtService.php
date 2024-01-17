<?php

namespace App\Service;

use Firebase\JWT\JWT;

class JwtService
{
    private $secretKey;

    public function __construct(string $secretKey)
    {
        $this->secretKey = $secretKey;
    }

    public function generateToken(array $data, int $expiration = 3600): string
    {
        $issuedAt = time();
        $expire = $issuedAt + $expiration;

        $token = [
            "iat" => $issuedAt,
            "exp" => $expire,
            "data" => $data,
        ];

        return JWT::encode($token, $this->secretKey, "HS256");
    }

    public function decodeToken(string $token): array
    {
        return (array) JWT::decode($token, $this->secretKey, ["HS256"]);
    }
}
