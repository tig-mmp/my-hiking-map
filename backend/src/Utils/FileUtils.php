<?php

namespace App\Utils;

use App\Dto\FileFormDto;
use App\Entity\File;
use App\Entity\Landmark;
use App\Entity\Track;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class FileUtils
{
    public function manageFile(EntityManager $entityManager, ?FileFormDto $parameters, ?Track $track, ?Landmark $landmark): ?File
    {
        if (!$parameters) {
            return null;
        }
        if ($parameters->getId()) {
            if ($landmark && $landmark->getFileId()) {
                if ($landmark->getFile()->getUrl() === $parameters->getUrl()) {
                    return $landmark->getFile();
                } else {
                    @unlink($landmark->getFile()->getUrl());
                    $entityManager->remove($track->getFile());
                }
            }
            if (!$landmark && $track && $track->getFileId()) {
                if ($track->getFile()->getUrl() === $parameters->getUrl()) {
                    return $track->getFile();
                } else {
                    @unlink($track->getFile()->getUrl());
                    $entityManager->remove($track->getFile());
                }
            }
        }

        $trackId = $track ? $track->getId() : ($landmark ? $landmark->getTrackId() : null);
        $landmarkId = $landmark ? $landmark->getId() : null;
        $urlTemp = $parameters->getUrl();
        $urlPath = explode("/", $urlTemp);
        $filename = $urlPath[sizeof($urlPath) - 1];

        $newFilepath = ($trackId && $landmarkId
            ? "files/$trackId/$landmarkId/$filename"
            : ($trackId && !$landmarkId
                ? "files/$trackId/$filename"
                : (!$trackId && $landmarkId ? "files/landmarks/$filename" : "files/$filename")
            ));

        $path = pathinfo($newFilepath);
        if (!file_exists($path["dirname"])) {
            mkdir($path["dirname"], 0777, true);
        }
        if (!copy($urlTemp, $newFilepath)) {
            throw new HttpException("failed_to_copy", Response::HTTP_CONFLICT);
        }
        unlink($urlTemp);

        $file = new File();
        $file->setName($parameters->getName());
        $file->setUrl($newFilepath);
        if ($track) {
            $track->setFile($file);
        }
        if ($landmark) {
            $landmark->setFile($file);
        }
        $entityManager->persist($file);
        return $file;
    }
}
