<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;
use Throwable;

class FilesController extends AbstractController
{
    #[Route("/api/upload", name: "upload-file", methods: ["POST"])]
    public function upload(): Response
    {
        $file = $_FILES["file"];
        if (!file_exists("tmp")) {
            mkdir("tmp", 0777, true);
        }
        $name = $file["name"];

        $uuid = Uuid::v4();
        $extension = pathinfo($name)["extension"];
        $newFilename = $uuid . "." . $extension;

        if (!copy($file["tmp_name"], "tmp/" . $newFilename)) {
            return $this->json(["msg_code" => "failed_to_copy"], 409);
        }
        $url = "tmp/" . $newFilename;

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
        return $this->json(["msg_code" => "file_saved", "data" => ["name" => $name, "url" => $url]]);
    }

    #[Route("/api/file", name: "get-file", methods: ["GET"])]
    public function getFile(Request $request): Response
    {
        $url = $request->query->get("url");
        try {
            if (str_starts_with($url, "tmp")) {
                $files = glob($url . ".*");
                if (!empty($files)) {
                    $file = $files[0];
                    return $this->file($file);
                }
            } else {

                return $this->file("../$url");
            }
        } catch (Throwable $th) {
            return $this->json(["msg_code" => $th->getMessage()]);
        }
        return $this->json([Response::HTTP_NOT_FOUND, "file_not_found"]);
    }
}
