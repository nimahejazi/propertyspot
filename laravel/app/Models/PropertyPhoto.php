<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyPhoto extends Model
{
    use HasFactory;

    public $fillable = [
        'image_url',
        'image_url_2x',
        'thumb_url',
        'thumb_url_2x',
    ];

    public function listing() {
        $this->belongsTo('App\Model\Listing');
    }
}
