<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function scopeWhereOpenHours($query, $time = null, $day = null)
    {
        if($time && $day) {
            return $query->join('schedules', 'schedules.restaurant_id', '=', 'restaurants.id')
                ->whereJsonContains('dayname', [$day])
                ->whereRaw('(open < closed AND ? >= open AND ? < closed) OR (closed < open AND (? < closed or ? >= open))', [$time, $time, $time, $time])
                ->groupBy('schedules.restaurant_id');
                
        } elseif($time) {
            return $query->join('schedules', 'schedules.restaurant_id', '=', 'restaurants.id')
                ->whereRaw('(open < closed AND ? >= open AND ? < closed) OR (closed < open AND (? < closed or ? >= open))', [$time, $time, $time, $time])
                ->groupBy('schedules.restaurant_id');

        } else {
            return $query->join('schedules', 'schedules.restaurant_id', '=', 'restaurants.id')->whereJsonContains('dayname', [$day])
            ->groupBy('schedules.restaurant_id');
        }
    }

    public function scopeWhereOpenDays($query, $dayName) 
    {
        return $query->whereHas('schedules', function ($query) use ($dayName) {
            $query->where('dayname', $dayName);
        });
    }

    public function scopeGenerateSchedule($query, $schedule, $restaurantId) 
    {
        return [
            'dayname' => json_encode($schedule['dayname']),
            'open' => $schedule['open'],
            'closed' => $schedule['closed'],
            'restaurant_id' => $restaurantId,
        ];
    }
}
