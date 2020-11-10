<?php

namespace App\Models;

use App\Events\PasswordResetRequested;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

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
        'phone',
        'license_no',
        'has_company',
        'company_name',
        'company_website',
        'company_address',
        'api_token'
    ];

    protected $dispatchesEvents = [
        'created'   => Registered::class
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

    protected $dates = [
        'reset_token_requested_at'
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

    public function resetPassword() {
        $this->reset_token = Str::random(80);
        $this->reset_token_requested_at = Carbon::now();
        $this->save();
        event(new PasswordResetRequested($this));
    }

    public function isResetTokenValid($token) {
        return (
            $this->reset_token == $token &&
            $this->reset_token_requested_at &&
            $this->reset_token_requested_at->addMinutes(30) >= Carbon::now()
        );
    }

}
