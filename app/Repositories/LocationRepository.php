<?php

namespace App\Repositories;

use App\Repositories\Interface\LocationRepositoryInterface;


class LocationRepository implements LocationRepositoryInterface
{
    public function index($request)
    {
        return 1;
    }
    
}