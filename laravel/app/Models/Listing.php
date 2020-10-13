<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    use HasFactory;

    public $fillable = [
        'street',
        'add_line2',
        'county',
        'city',
        'state',
        'zip',
        'lat',
        'lng',
        'elementary_school',
        'middle_school',
        'high_school',
        'property_type_id',
        'bedrooms',
        'bathrooms',
        'square_ft',
        'price',
        'mls_no',
        'listing_status_id',
        'year_built',
        'lot_square_ft',
        'floors',
        'garage_size',
        'property_desc',
        'slug',
        'square_customer_id',
    ];

    public function amenities() {
        return $this->hasMany('App\Models\PropertyAmenity');
    }
    public function photos() {
        return $this->hasMany('App\Models\PropertyPhoto');
    }
    public function videos() {
        return $this->hasMany('App\Models\PropertyVideo');
    }

    public function status() {
        return $this->hasOne('App\Models\ListingStatus');
    }

    public function user() {
        return $this->belongsTo('App\Models\User');
}
}
