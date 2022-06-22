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

    public function scopeWhereOpenHours($query, $open = null, $closed = null)
    {
        $open = $open ?? Carbon::now()->format('H:i');

        $closed = $closed ?? Carbon::now()->format('H:i');

        return $query->whereHas('schedules', function ($query) use ($open, $closed) {
            $query->where('open', '<=', $open)->where('closed', '>', $closed);
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
