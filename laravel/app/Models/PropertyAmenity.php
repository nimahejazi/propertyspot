<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyAmenity extends Model
{
    use HasFactory;

    public $fillable = [
        'amenity',
        'is_custom'
    ];

    public function listing() {
        $this->belongsTo('App\Models\Listing');
    }
}
