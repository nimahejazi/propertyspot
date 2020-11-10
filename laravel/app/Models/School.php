<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    public $fillable = [
        'name',
        'type',
        'grade_range',
        'elementary_school',
        'middle_school',
        'high_school',
        'enrollment',
        'gs_rating',
        'parent_rating',
        'city',
        'state',
        'district',
        'address',
        'phone',
        'website',
        'lat',
        'lng',
        'distance',
        'listing_id',
    ];

    public function Listing() {
        return $this->belongsTo('App\Models\Listing');
    }
}
