<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class OtpVerification extends Model
{
    protected $fillable = ['phone', 'code', 'expires_at', 'is_verified'];

    protected $dates = ['expires_at'];

    public function isExpired(){
        return $this->expires_at->isPast();
    }
}
