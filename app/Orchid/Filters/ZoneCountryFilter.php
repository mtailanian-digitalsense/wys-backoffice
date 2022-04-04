<?php

namespace App\Orchid\Filters;

use App\Models\Category;
use App\Models\Country;
use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layout;
use Orchid\Screen\Layouts\Rows;

class ZoneCountryFilter extends Filter
{
    /**
     * @var array
     */
    public $parameters = [
        'country'
    ];

    /**
     * @return string
     */
    public function name(): string
    {
        return 'País';
    }

    /**
     * @param Builder $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        return $builder->whereHas('zone.region.country', function (Builder $query) {
            $query->where('name', $this->request->get('country'));
        });
    }

    /**
     * @return Field[]
     */
    public function display(): array
    {
        return [
            Select::make('country')
                ->fromModel(Country::class, 'name', 'name')
                ->empty()
                ->value($this->request->get('country'))
                ->title(__('País')),
        ];
    }
}
