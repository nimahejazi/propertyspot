<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyVideo extends Model
{
    use HasFactory;

    public $fillable = [
        'provider',
        'video_id'
    ];

    public function listing() {
        $this->belongsTo('App\Model\Listing');
    }

}
