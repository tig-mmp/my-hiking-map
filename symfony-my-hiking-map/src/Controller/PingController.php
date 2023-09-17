<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PingController extends AbstractController
{
    #[Route("/api/ping", name: "ping", methods: ["GET"])]
    public function ping(): Response
    {
        return $this->json("PONG", 200);
    }
}
