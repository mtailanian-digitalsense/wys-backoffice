<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Filters\Filterable;

class Space extends Model
{

    use Filterable;

    protected $fillable = [
        'id', 'name', 'subcategory_id', 'active', 'down_gap', 'height',
        'left_gap', 'regular', 'right_gap', 'up_gap', 'width'
    ];

    /**
     * The attributes for which you can use filters in url.
     *
     * @var array
     */
    protected $allowedFilters = [
        'id',
        'name',
        'subcategory_id',
    ];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'name',
        'subcategory_id',
    ];


    /**
     * @return BelongsTo
     */
    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class, 'subcategory_id');
    }
}
