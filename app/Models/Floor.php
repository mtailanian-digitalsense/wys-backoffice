<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Filters\Filterable;


class Floor extends Model
{
    use Filterable;

    /**
     * The attributes for which you can use filters in url.
     *
     * @var array
     */
    protected $allowedFilters = [
        "id",
        "wys_id",
    ];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'wys_id',
    ];

    protected $fillable = [
        "id",
        "elevators_number",
        "image_link",
        "m2",
        "rent_value",
        "wys_id",
        "building_id",
    ];

    /**
     * @return BelongsTo
     */
    public function building()
    {
        return $this->belongsTo(Building::class, 'building_id');
    }

}
