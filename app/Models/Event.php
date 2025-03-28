<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $fillable = [
        'restaurant_id',
        'title',
        'description',
        'event_date',
    ];
    

    public function event_images()
    {
        return $this->hasMany(Image::class);
    }
}
