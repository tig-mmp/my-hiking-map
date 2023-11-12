<?php

namespace App\Controller\Api;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LoginController extends AbstractController
{
    #[Route("/api/login", name: "api_login")]
    public function index(#[CurrentUser] ?User $user): Response
    {
        if (null === $user) {
            return $this->json([
                "message" => "missing credentials",
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = "";
        return $this->json([
            "user"  => $user->getUserIdentifier(),
            "token" => $token,
        ]);
    }
}
