<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fullname',
        'email',
        'password',
        'title',
        'license_no',
        'has_company',
        'company_name',
        'company_website',
        'company_address',
        'api_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Get email or fullname if available
    public function getName() {
        return $this->fullname ? ucfirst($this->fullname) : $this->email;
    }

    // To be used for avatar inital
    public function getInitial() {
        $name = $this->fullname ? substr($this->fullname, 0, 1) : substr($this->email, 0, 1);
        return strtoupper($name);
    }

    /**
     * Checks if user has complete profile, or partially completed and no profile
     * @return string 'complete'|'partially'|'empty'
     */
    public function userProfileStatus() {
        $allDetails = false;
        $userDetails = [
            'fullname',
            'license_no',
            'title',
            'photo_url'
        ];

        foreach ($userDetails as $detail) {
            $allDetails = $this[$detail] ? true : false;
        }
        if ($allDetails) {
            return 'complete';
        }
        foreach ($userDetails as $detail) {
            if ($this[$detail]) return 'partially';
        }
        return 'empty';

    }

    public function listings() {
        return $this->hasMany('App\Models\Listing');
    }


}
