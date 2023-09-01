<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\RestaurantController;

// resturant finder api routes
Route::get('restaurants', [RestaurantController::class, 'getRestaurants']);

