<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Orchid\Filters\Filterable;

class Category extends Model
{
    use Filterable;

    protected $fillable = [
        'name', 'id',
    ];


    /**
     * The attributes for which you can use filters in url.
     *
     * @var array
     */
    protected $allowedFilters = [
        'id',
        'name',
    ];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'name',
    ];


    /**
     * @return HasMany
     */
    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }

}
