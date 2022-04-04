<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'name', 'short_code'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

}
