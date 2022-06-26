<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RestaurantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
       $openSchedule = [];
       foreach($this->schedules as $openHours) {
            $dayNames = json_decode($openHours->dayname);

            $firstDay = $dayNames[0];
            $endDay = count($dayNames) > 1 ? end($dayNames) : null;

            $schedule = $endDay ? $firstDay.' - '.$endDay : $firstDay;

            $openSchedule[] = $schedule . ' ' . $openHours->open . ' - ' . $openHours->closed;
       }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'schedules' => $openSchedule,
        ];
    }
}
