<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class RestaurantController extends Controller
{
    // get restaurants
    public function getRestaurants(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'city' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'distance' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'msg' => $validator->errors()->first(),
            ], 400);
        }

        try {

            if ($request->has('latitude')) {
                $restaurants = Restaurant::where('latitude', $request['latitude']);
            } elseif ($request->has('longitude')) {
                $restaurants = Restaurant::where('longitude', $request['longitude']);
            } elseif ($request->has('distance')) {
                $restaurants = Restaurant::where('distance', $request['distance']);
            } elseif ($request->has('city')) {
                $key = explode(' ', $request['city']);
                $restaurants = Restaurant::where(function ($q) use ($key) {
                        foreach ($key as $value) {
                            $q->orWhere('city', 'like', "%{$value}%");
                        }
                    });
            } else {
                $restaurants = new Restaurant();
            }

            $restaurants = $restaurants->latest()->simplePaginate(10);

            if (count($restaurants) > 0) {
                $data = array();
                for ($i = 0; $i < count($restaurants); $i++) {

                    $data[$i]['name'] = $restaurants[$i]->author_id;
                    $data[$i]['address'] = $restaurants[$i]->address;
                    $data[$i]['longitude'] = $restaurants[$i]->longitude;
                    $data[$i]['latitude'] = $restaurants[$i]->latitude;
                }

                return response()->json(
                    ['restaurants' => $restaurants],
                    200
                );

            } else {
                return response()->json(
                    ['message' => 'No restuarant found'],
                    200
                );
            }

        } catch (\Throwable $th) {
            return response()->json(
                ['message' => $th->getMessage()],
                400
            );
        }
    }


}
