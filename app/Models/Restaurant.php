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
        $time = $time ?? Carbon::now()->format('H:i');

        $day = $day ?? Carbon::now()->format('D');

        return $query->whereHas('schedules', function ($query) use ($time, $day) {
            if($time && $day) {
                $query->where('open', '<=', $time)->where('closed', '>', $time)->whereJsonContains('dayname', $day);
            } elseif($time) {
                $query->where('open', '<=', $time)->where('closed', '>', $time);
            } else {
                $query->whereJsonContains('dayname', $day);
            }
        });
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
