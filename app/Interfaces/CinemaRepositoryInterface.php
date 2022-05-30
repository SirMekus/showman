<?php

namespace App\Interfaces;

interface CinemaRepositoryInterface 
{
    public function getAllCinemas();
    public function getCinemaById($orderId);
    public function deleteCinema($orderId);
    public function createOrder(array $orderDetails);
    public function updateOrder($orderId, array $newDetails);
    public function getFulfilledOrders();
}