<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;
use Throwable;

class FilesController extends AbstractController
{
    #[Route("/api/upload", name: "upload-file", methods: ["POST"])]
    public function uploadFile(): Response
    {
        $file = $_FILES["file"];
        if (!file_exists("tmp")) {
            mkdir("tmp", 0777, true);
        }
        $data = $this->copyFileToTmpFolder($file["name"], $file["tmp_name"]);
        $this->clearTmpFiles();
        return $this->json(["msg_code" => "file_saved", "data" => $data]);
    }

    #[Route("/api/upload-multiple", name: "upload-multiple-files", methods: ["POST"])]
    public function uploadMultipleFiles(): Response
    {
        $files = $_FILES["files"];
        if (!file_exists("tmp")) {
            mkdir("tmp", 0777, true);
        }
        $data = [];
        for ($i = 0; $i < count($files["name"]); $i++) {
            $data[] = $this->copyFileToTmpFolder($files["name"][$i], $files["tmp_name"][$i]);
        }
        $this->clearTmpFiles();
        return $this->json(["msg_code" => "file_saved", "data" => $data]);
    }

    private function copyFileToTmpFolder(string $name, string $tmpUrl): array
    {
        $uuid = Uuid::v4();
        $extension = pathinfo($name)["extension"];
        $newFilename = $uuid . "." . $extension;

        if (!copy($tmpUrl, "tmp/" . $newFilename)) {
            throw new HttpException(Response::HTTP_CONFLICT, "failed_to_copy");
        }
        $url = "tmp/" . $newFilename;
        return ["name" => $name, "url" => $url];
    }

    private function clearTmpFiles()
    {
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

                return $this->file($url);
            }
        } catch (Throwable $th) {
            return $this->json(["msg_code" => $th->getMessage()]);
        }
        return $this->json([Response::HTTP_NOT_FOUND, "file_not_found"]);
    }
}
