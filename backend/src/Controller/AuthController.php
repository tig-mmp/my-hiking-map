<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\JwtService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    #[Route("/api/login", name: "login", methods: ["POST"])]

    public function login(Request $request, JwtService $jwtService, UserPasswordHasherInterface $passwordHasher, UserRepository $userRep): Response
    {
        $parameters = json_decode($request->getContent(), true);

        $username = $parameters["username"];
        $password = $parameters["password"];

        if (!$username || !$password) {
            return $this->json(["msg_code" => "Invalid credentials"], Response::HTTP_UNAUTHORIZED);
        }
        $user = $userRep->findOneBy(["username" => $username]);

        if (!$user || !$passwordHasher->isPasswordValid($user, $password)) {
            return $this->json(["msg_code" => "Invalid credentials"], Response::HTTP_UNAUTHORIZED);
        }
        $userData = ["role" => $user->getRole()];
        $userData = ["role" => "ADMIN"];
        $token = $jwtService->generateToken($userData);
        return $this->json(["token" => $token]);
    }
}
