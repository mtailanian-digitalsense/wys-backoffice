<?php

namespace App\Models;

use App\Orchid\Presenters\AdminPresenter;
use App\Orchid\Presenters\UserPresenter;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Orchid\Platform\Models\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Notifications\OrchidResetPassword;


class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes;

    protected $dates = ['deleted_at', 'confirmed_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'last_name',
        'email',
        'password',
        'country_id',
        'last_login',
        'active',
        'confirmation_token',
        'permissions',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'activation_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'permissions' => 'array',
        'email_verified_at' => 'datetime',
        'last_login' => 'datetime',
    ];

    /**
     * The attributes for which you can use filters in url.
     *
     * @var array
     */
    protected $allowedFilters = [
        'id',
        'name',
        'email',
        'permissions',
    ];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'name',
        'email',
        'last_login',
        'updated_at',
        'created_at',
    ];

    /**
     * Send the password reset notification.
     *
     * @param string $token
     *
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new OrchidResetPassword($token));
    }


    /**
     * @return HasMany
     */
    public function linkedSocialAccounts()
    {
        return $this->hasMany(LinkedSocialAccount::class);
    }

    /**
     * @return BelongsTo
     */
    public function countries()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Generate a random hexadecimal token by hashing the current time in microseconds as float
     *
     * @return string Random 32-characters long hexadecimal token
     */
    public static function generateToken()
    {
        return md5(microtime(true));
    }

    /**
     * Check if the confirmed_at field in the database is NULL or not
     *
     * @return bool Whether the user has confirmed his e-mail address or not
     */
    public function hasConfirmed()
    {
        return $this->confirmed_at != null;
    }

    public function presenter()
    {

        return new UserPresenter($this);

    }

    public function adminPresenter()
    {

        return new AdminPresenter($this);

    }

}
