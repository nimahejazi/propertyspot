<?php

namespace App\Models;

use App\Events\LeadCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;
    public $fillable = [
        'name',
        'email',
        'phone',
        'message',
        'listing_id'
    ];

    protected $dispatchesEvents = [
        'created' => LeadCreated::class
    ];

    public function Listing() {
        return $this->belongsTo('App\Models\Listing');
    }

}
