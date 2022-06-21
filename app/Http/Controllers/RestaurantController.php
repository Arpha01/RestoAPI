<?php

namespace App\Http\Controllers;

use App\Http\Requests\RestaurantRequest;
use App\Http\Resources\RestaurantResource;
use App\Models\Restaurant;
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
        
        $restaurants = Restaurant::orderBy('name');

        if($request->has('name')) {
            $restaurants = $restaurants->where('name', 'like', '%' . $request->name . '%');
        }

        if($request->has('open') || $request->has('closed')) {
            $restaurants = $restaurants->whereOpenHours($request->open, $request->closed);
        }

        if($request->has('day')) {
            $day = $request->day;
            $restaurants = $restaurants->whereHas('schedules', function($query) use ($day) {
                $query->where('day', 'like', '%' . $day . '%');
            });
        }

        $restaurants = $restaurants->paginate($limit);

        return RestaurantResource::collection($restaurants)->additional($this->additionalResource($restaurants));
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
            // $schedules = $request->schedules;

            // foreach($schedules as $schedule) {
            //     $restaurant->schedules()->create($schedule);
            // }

            // $dayName = explode('-', $request->day);

            // $dayStart = Carbon::parse($dayName[0]);
            // $dayEnd = null;
    
            // if(count($dayName) > 1) {
            //     $dayEnd = Carbon::parse($dayName[1]);
            // }
    
            // $diff = $dayStart->diffInDays($dayEnd ?? $dayStart);
    
            // for($i = 1; $i < $diff; $i++) {
            //     $restaurant->schedules()->create([
            //         'dayname' => $dayStart->addDays($i)->format('D'),
            //         'open' => $open,
            //         'closed' => $closed,
            //     ]);
            // }

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
            $restaurant->schedules()->create($schedule);
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
