<?php


namespace App\Orchid\Layouts\Zone;

use App\Models\Country;
use App\Orchid\Filters\CountryFilter;
use Orchid\Filters\Filter;
use Orchid\Screen\Layouts\Selection;

class ZoneFiltersLayout extends Selection
{
    /**
     * @return Filter[]
     */
    public function filters(): array
    {
        return [
           CountryFilter::class,
        ];
    }
}
