<?php

namespace App\Utils;

use App\Entity\County;
use App\Entity\District;
use App\Entity\Location;
use App\Entity\Track;
use App\Repository\CountyRepository;
use App\Repository\DistrictRepository;
use App\Repository\LocationRepository;
use Doctrine\ORM\EntityManager;

class LocationUtils
{
    public function setStartDistrict(EntityManager $entityManager, ?int $id, ?string $name, DistrictRepository $districtRep): ?District
    {
        if (!$id && !$name) {
            return null;
        }
        if (!$id && $name) {
            $district = $districtRep->findOneBy(["name" => $name]);
            if (!$district) {
                $district = new District($name);
                $entityManager->persist($district);
            }
        } else {
            $district = $districtRep->findOneBy(["id" => $id]);
        }
        return $district;
    }

    public function setStartCounty(EntityManager $entityManager, ?int $id, ?string $name, District $district, CountyRepository $countyRep): ?County
    {
        if (!$id && !$name) {
            return null;
        }
        if (!$id && $name) {
            $county = $countyRep->findOneBy(["name" => $name]);
            if (!$county) {
                $county = new County($name, $district);
                $entityManager->persist($county);
            }
        } else {
            $county = $countyRep->findOneBy(["id" => $id]);
        }
        return $county;
    }

    public function setStartLocation(EntityManager $entityManager, Track $track, ?int $id, ?string $name, County $county, LocationRepository $locationRep): ?Location
    {
        if (!$id && !$name) {
            return null;
        }
        if (!$id && $name) {
            $location = $locationRep->findOneBy(["name" => $name]);
            if (!$location) {
                $location = new Location($name, $county);
                $entityManager->persist($location);
            }
        } else {
            $location = $locationRep->findOneBy(["id" => $id]);
        }
        $track->setStartLocation($location);
        return $location;
    }
}
