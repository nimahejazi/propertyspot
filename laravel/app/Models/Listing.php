<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    use HasFactory;

    public $fillable = [
        'user_id',
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
        'property_type',
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
        'featured_photo_id',
    ];

    public function amenities() {
        return $this->hasMany('App\Models\PropertyAmenity');
    }
    public function photos() {
        return $this->hasMany('App\Models\PropertyPhoto', 'key');
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

    public function schools() {
        return $this->hasMany('App\Models\School');
    }

    public function leads() {
        return $this->hasMany('App\Models\Lead');
    }

    public function featuredPhotoThumb() {
        if ($this->featured_photo_id) {
            $photo =  PropertyPhoto::where('id', $this->featured_photo_id)->first();
            if ($photo) return $photo->thumb_2x_url;
            return null;

        } else {
            $photo = PropertyPhoto::where('key', $this->id)
                ->orderBy('position')
                ->first();
            if ($photo) return $photo->thumb_2x_url;
            return null;
        }
    }
    public function featuredPhoto() {
        if ($this->featured_photo_id) {
            $photo =  PropertyPhoto::where('id', $this->featured_photo_id)->first();
            if ($photo) return ['img' => $photo->image_url, 'img_2x' => $photo->image_2x_url];
            return null;

        } else {
            $photo = PropertyPhoto::where('key', $this->id)
                ->orderBy('position')
                ->first();
            if ($photo) return ['img' => $photo->image_url, 'img_2x' => $photo->image_2x_url];
            return null;
        }
    }

    public function hasPhotos() {
        return $this->photos()->count() > 0;
    }

    public function nextStep() {
        if (!$this->hasPhotos()) {
            return [
                'title' => 'Upload Property Photos',
                'url'   => '/users/new-listing/' . $this->id . '/#/photos'
            ];
        }
        if (!$this->featured_photo_id) {
            return [
                'title' => 'Set Featured Photo',
                'url'   => '/users/new-listing/' . $this->id . '/#/featured-photo'
            ];

        }
        if ($this->payment_status !== 'paid' && $this->payment_status !== 'processing') {
            return [
                'title' => 'Pay and Publish the Website',
                'url'   => '/users/payment/' . $this->id
            ];
        }
    }

    public function createSlug() {
        $slug = strtolower(str_replace(' ', '', $this->street));
        $slugBase = $slug;
        $i = 2;
        while(Listing::where('slug', $slug)->count()) {
            $slug = $slugBase . '-' . $i++;
        }
        return $slug;
    }

    public function isLive() {
        return $this->payment_status === 'paid' && $this->slug;
    }

    public function getAddress() {
        $address = [];
        if ($this->street) $address[] = $this->street;
        if ($this->add_line2) $address[] = $this->add_line2;
        if ($this->city) $address[] = $this->city;
        if ($this->state) $address[] = $this->state;
        $retAddress = implode(', ', $address);
        if ($this->zip) $retAddress .= ' ' . $this->zip;
        if (!$retAddress) return 'Address undisclosed';
        return $retAddress;
    }

    public function paymentStatus() {
        return $this->hasOne('App\Models\PaymentStatus');
    }
}
