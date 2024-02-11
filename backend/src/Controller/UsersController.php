<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Requests\Users\User\UserRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{
    public $MIDDLEWARE = "jwt";

    #[Route("/api/users", name: "list-users", methods: ["GET"])]
    public function listUsers(UserRepository $userRep): JsonResponse
    {
        $users = $userRep->findAll();
        $usersFormatted = [];
        foreach ($users as $user) {
            $usersFormatted[] = $user->serialize();
        }
        return $this->json(["data" => $usersFormatted]);
    }

    #[Route("/api/users/{id<\d+>}", name: "find-user", methods: ["GET"])]
    public function findUser(int $id, UserRepository $userRep): JsonResponse
    {
        $user = $userRep->findOneBy(["id" => $id]);
        if (!$user) {
            return $this->json([Response::HTTP_NOT_FOUND, "user_not_found"]);
        }
        $userFormatted = $user->serialize();
        return $this->json(["data" => $userFormatted]);
    }

    #[Route("/api/users", name: "create-user", methods: ["POST"])]
    public function createUser(EntityManagerInterface $entityManager, Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $parameters = json_decode($request->getContent(), true);
        $parameters = new UserRequest($parameters);

        $user = new User();
        $this->fill($user, $parameters, $passwordHasher);
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json(["msg_code" => "user_created"]);
    }

    #[Route("/api/users/{id<\d+>}", name: "update-user", methods: ["PUT"])]
    public function updateUser(
        int $id, EntityManagerInterface $entityManager, Request $request,
        UserPasswordHasherInterface $passwordHasher, UserRepository $userRep
    ): JsonResponse {
        $user = $userRep->findOneBy(["id" => $id]);
        if (!$user) {
            throw new HttpException(Response::HTTP_NOT_FOUND, "user_not_found");
        }

        $parameters = json_decode($request->getContent(), true);
        $parameters = new UserRequest($parameters);

        $this->fill($user, $parameters, $passwordHasher);

        $entityManager->flush();
        return $this->json(["msg_code" => "user_updated"]);
    }

    private function fill(User $user, UserRequest $parameters, UserPasswordHasherInterface $passwordHasher)
    {
        $user->setUsername($parameters->getUsername());
        $user->setRole($parameters->getRole());
        if ($parameters->getPassword()) {
            $hashedPassword = $passwordHasher->hashPassword($user, $parameters->getPassword());
            $user->setPassword($hashedPassword);
        }
    }
}
