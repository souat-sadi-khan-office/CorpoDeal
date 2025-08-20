<?php

namespace App\Repositories\Interface;

interface CityRepositoryInterface
{
    public function getAllCities();
    public function getAllActiveCity();
    public function dataTable();
    public function findCityById($id);
    public function createCity(array $data);
    public function updateCity($id, array $data);
    public function deleteCity($id);
    public function updateStatus($request, $id);
    public function findCitiesByCountryId($countryId);
}
