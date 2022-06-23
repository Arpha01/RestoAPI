<?php

namespace App\Models;

use Attribute;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $primaryKey = null;
    protected $guarded = [];

    public $timestamps = false;
    public $incrementing = false;

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
