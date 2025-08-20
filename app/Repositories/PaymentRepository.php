<?php

namespace App\Repositories;

use App\Repositories\Interface\PaymentRepositoryInterface;


class PaymentRepository implements PaymentRepositoryInterface
{
    public function index($request)
    {
        return 1;
    }
    
}