<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = ['restaurant_id', 'image', 'event_id'];

    function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    function event()
    {
        return $this->belongsTo(Event::class);
    }
}
