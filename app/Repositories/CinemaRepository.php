<?php

namespace App\Repositories;

use App\Interfaces\CinemaRepositoryInterface;
use App\Models\Cinema;
use Illuminate\Database\Eloquent\Builder;

class CinemaRepository implements CinemaRepositoryInterface 
{
    public function getAllCinemas() 
    {
        return request()->user()->with(['cinema'=>function($query){
            $query->withCount('movies');
        }])->find(request()->user()->id);
    }

    public function getCinemaById($orderId) 
    {
        return Cinema::findOrFail($orderId);
    }

    public function deleteCinema($orderId) 
    {
        Cinema::destroy($orderId);
    }

    public function createOrder(array $orderDetails) 
    {
        return Cinema::create($orderDetails);
    }

    public function updateOrder($orderId, array $newDetails) 
    {
        return Cinema::whereId($orderId)->update($newDetails);
    }

    public function getFulfilledOrders() 
    {
        return Cinema::where('is_fulfilled', true);
    }
}
