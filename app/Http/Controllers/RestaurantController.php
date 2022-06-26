<?php

namespace App\Http\Controllers;

use App\Http\Requests\RestaurantRequest;
use App\Http\Resources\RestaurantResource;
use App\Models\Restaurant;
use App\Models\Schedule;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);

        $direction = $request->input('direction', 'asc');
        $attribute = $request->input('attribute', 'id');
        
        $restaurants = Restaurant::orderBy($attribute, $direction);

        if($request->has('name')) {
            $restaurants = $restaurants->where('name', 'like', '%' . $request->name . '%');
        }

        $day = $request->input('day');
        $time = $request->input('time');

        if($request->has('time') && $request->has('day')) {
            $restaurants = $restaurants->whereOpenHours($time, $day);
        } elseif($request->has('time')) {
            $restaurants = $restaurants->whereOpenHours($time);
        } elseif($request->has('day')) {
            $restaurants = $restaurants->whereOpenDays($day);
        }

        $restaurants = $restaurants->paginate($limit);

        $restaurantInstance = $request->has('name') || $request->has('time') || $request->has('day') 
             ? $restaurants : Restaurant::count();

        return RestaurantResource::collection($restaurants)->additional(
            $this->additionalResource($restaurantInstance)
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RestaurantRequest $request)
    {
        $restaurant = new Restaurant($request->only('name'));

        if($restaurant->save()) {
            $schedules = $request->schedules;

            foreach($schedules as $schedule) {
                $openHours = new Schedule(Restaurant::generateSchedule($schedule, $restaurant->id));

                $openHours->save();
            }

            return (new RestaurantResource($restaurant))
                ->additional($this->additionalResource($restaurant));
        }

        return ErrorHandler::errorResource('Gagal menyimpan data', 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $restaurant  = Restaurant::find($id);

        if(!$restaurant) {
            return ErrorHandler::errorResource('Restoran tidak ditemukan', 404);
        }

        return (new RestaurantResource($restaurant))
            ->additional($this->additionalResource($restaurant));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RestaurantRequest $request, $id)
    {
        $restaurant = Restaurant::with('schedules')->find($id);

        if(!$restaurant) {
            return ErrorHandler::errorResource('Tidak ditemukan data', 404);
        }

        $restaurant->name = $request->name;

        $restaurant->schedules()->delete();
        $schedules = $request->schedules;

        foreach($schedules as $schedule) {
            $openHours = new Schedule(Restaurant::generateSchedule($schedule, $restaurant->id));

            $openHours->save();
        }

        $restaurant->load('schedules');

        if($restaurant->save()) {
            return (new RestaurantResource($restaurant))
                ->additional($this->additionalResource($restaurant));
        }

        return ErrorHandler::errorResource('Gagal mengupdate data', 400);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $restaurant = Restaurant::find($id);

        if(!$restaurant) {
            return ErrorHandler::errorResource('Tidak ditemukan data', 404);
        }

        if($restaurant->delete()) {
            return [
                'success' => true, 
                'message' => 'Data berhasil dihapus'
            ];
        }
    }
}
