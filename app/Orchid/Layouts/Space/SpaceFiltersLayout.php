<?php


namespace App\Orchid\Layouts\Space;

use App\Orchid\Filters\CategoryFilter;
use App\Orchid\Filters\SubcategoryFilter;
use Orchid\Filters\Filter;
use Orchid\Screen\Layouts\Selection;

class SpaceFiltersLayout extends Selection
{
    /**
     * @return Filter[]
     */
    public function filters(): array
    {
        return [
            SubcategoryFilter::class,
            CategoryFilter::class,
        ];
    }
}
