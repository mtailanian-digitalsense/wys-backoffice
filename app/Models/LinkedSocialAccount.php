<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class LinkedSocialAccount extends Model
{
    const SERVICE_FACEBOOK = 'facebook';
    const SERVICE_GOOGLE = 'google';
    const SERVICE_LINKEDIN = 'linkedin';

    protected $fillable = [
        'provider_name',
        'provider_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
