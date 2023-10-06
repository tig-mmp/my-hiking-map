<?php

namespace App\Controller\Api;

use App\Repository\MapRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Route;
use Symfony\Component\Uid\Uuid;

class FilesController extends AbstractController
{
    #[Route("/api/save", name: "save-file", methods: ["POST"])]
    public function save(): Response
    {
        $files = $_FILES["files"];
        if (!file_exists("tmp")) {
            mkdir("tmp", 0777, true);
        }
        $response = [];

        if (is_array($files["name"])) {
            for ($i = 0; $i < count($files["name"]); $i++) {
                $uuid = Uuid::v4();
                $extension = pathinfo($files["name"][$i])["extension"];
                $newFilename = $uuid . "." . $extension;

                if (!copy($files["tmp_name"][$i], "tmp/" . $newFilename)) {
                    return $this->json(["msg_code" => "failed_to_copy"], 409);
                }
                $fileUrl = "tmp/" . $newFilename;
                array_push($response, [
                    "name" => $files["name"][$i],
                    "realFilename" => $files["name"][$i],
                    "url" => $fileUrl
                ]);
            }
        }
        if ($handle = opendir("tmp/")) {
            while (false !== ($file = readdir($handle))) {
                $fPath = "tmp/$file";
                if (!is_dir($fPath) && file_exists($fPath)) {
                    $fileLastModified = filemtime($fPath);
                    if ((time() - $fileLastModified) > 3600) {
                        unlink($fPath);
                    }
                }
            }
            closedir($handle);
        }

        return $this->json(["msg_code" => "file_saved", "data" => $response]);
    }

    #[Route("/api/persist", name: "save-file", methods: ["POST"])]
    public function persist(EntityManagerInterface $entityManager, Request $request, MapRepository $mapRep): Response
    {
        $parameters = json_decode($request->getContent(), true);
        $response = [];
        $files = $parameters["files"];
        foreach ($files as $parameter) {
            $mapId = $parameter["mapId"];
            $urlTemp = $parameter["url"];
            $urlPath = explode("/", $urlTemp);
            $filename = $urlPath[sizeof($urlPath) - 1];

            $newFilepath = "files/$mapId/$filename";

            $url = $newFilepath;
            $newFilepath = "../" . $newFilepath;
            $path = pathinfo($newFilepath);
            if (!file_exists($path["dirname"])) {
                mkdir($path["dirname"], 0777, true);
            }
            if (!copy($urlTemp, $newFilepath)) {
                return $this->json(["msg_code" => "failed_to_copy"], 409);
            }
            unlink($urlTemp);

            $map = $mapRep->findOneBy(["id" => $mapId]);
            if ($map) {
                $map->setUrl($url);
            }
        }
        $entityManager->flush();

        return $this->json(["msg_code" => "file_persisted", "data" => $response]);
    }
}
