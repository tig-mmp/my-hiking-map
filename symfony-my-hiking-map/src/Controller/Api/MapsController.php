<?php

namespace App\Controller\Api;

use App\Entity\Map;
use App\Repository\MapRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MapsController extends AbstractController
{
    public $MIDDLEWARE = "User";

    #[Route("/api/maps", name: "list-maps", methods: ["GET"])]
    public function listMaps(MapRepository $mapRep): Response
    {
        $maps = $mapRep->findAll();
        $mapsFormatted = [];
        foreach ($maps as $map) {
            $mapsFormatted[] = $map->serialize();
        }
        return $this->json(["data" => $mapsFormatted]);
    }

    #[Route("/api/maps/{id<\d+>}", name: "create-map", methods: ["POST"])]
    public function createMap(EntityManagerInterface $entityManager, Request $request): Response
    {
        $parameters = json_decode($request->getContent(), true);
        $map = new Map();
        $map->setName($parameters["name"]);
        $entityManager->persist($map);

        $entityManager->flush();
        return $this->json(["msg_code" => "map_created"]);
    }

    #[Route("/api/maps/{id<\d+>}", name: "update-map", methods: ["POST"])]
    public function updateMap(int $id, EntityManagerInterface $entityManager, Request $request, MapRepository $mapRep): Response
    {
        $parameters = json_decode($request->getContent(), true);
        $map = $mapRep->findOneBy(["id" => $id]);
        if ($map) {
            $map->setName($parameters["name"]);
        }

        $entityManager->flush();
        return $this->json(["msg_code" => "map_updated"]);
    }
}
