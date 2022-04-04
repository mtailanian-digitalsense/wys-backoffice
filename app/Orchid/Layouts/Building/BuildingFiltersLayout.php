<?php


namespace App\Orchid\Layouts\Building;

use App\Orchid\Filters\CategoryFilter;
use App\Orchid\Filters\CountryFilter;
use App\Orchid\Filters\FloorFilter;
use App\Orchid\Filters\RegionFilter;
use App\Orchid\Filters\SubcategoryFilter;
use App\Orchid\Filters\ZoneCountryFilter;
use App\Orchid\Filters\ZoneFilter;
use Orchid\Filters\Filter;
use Orchid\Screen\Layouts\Selection;

class BuildingFiltersLayout extends Selection
{

    /**
     * @var string
     */
    //public $template = self::TEMPLATE_LINE; //  1.Line

    /**
     * @return Filter[]
     */
    public function filters(): array
    {
        return [
            ZoneCountryFilter::class,
            ZoneFilter::class,
            RegionFilter::class,
            FloorFilter::class,
        ];
    }
}
