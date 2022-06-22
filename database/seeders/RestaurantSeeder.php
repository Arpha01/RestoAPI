<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use App\Models\Schedule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $restaurant = new Restaurant();
        $restaurant->name = 'Kushi Tsuru';

        $restaurant->save();

        Schedule::create([
            'dayname' => json_encode(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']),
            'open' => '11:30',
            'closed' => '21:00',
            'restaurant_id' => $restaurant->id,
        ]);

    }
}
