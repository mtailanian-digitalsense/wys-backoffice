<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Orchid\Filters\Filterable;


class Building extends Model
{
    use Filterable;

    /**
     * The attributes for which you can use filters in url.
     *
     * @var array
     */
    protected $allowedFilters = [
        "id",
        "name",
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

    protected $fillable = [
        "id",
        "active",
        "address_number",
        "adm_agility",
        "building_year",
        "category",
        "gps_location",
        "infrastructure_lvl",
        "name",
        "parking_lvl",
        "parking_number",
        "planta_tipo",
        "public_transport_lvl",
        "security_lvl",
        "services_lvl",
        "street",
        "sustainability_lvl",
        "total_floors",
        "view_lvl",
        "zone_id"
    ];

    /**
     * @return BelongsTo
     */
    public function zone()
    {
        return $this->belongsTo(Zone::class, 'zone_id');
    }

    /**
     * @return HasMany
     */
    public function floors()
    {
        return $this->hasMany(Floor::class);
    }


}
