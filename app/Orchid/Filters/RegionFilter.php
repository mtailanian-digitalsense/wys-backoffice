<?php

namespace App\Orchid\Filters;

use App\Models\Category;
use App\Models\Country;
use App\Models\Region;
use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;

class RegionFilter extends Filter
{
    /**
     * @var array
     */
    public $parameters = [
        'region'
    ];

    /**
     * @return string
     */
    public function name(): string
    {
        return 'Ciudad';
    }

    /**
     * @param Builder $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        return $builder->whereHas('zone.region', function (Builder $query) {
            $query->where('name', $this->request->get('region'));
        });
    }

    /**
     * @return Field[]
     */
    public function display(): array
    {
        return [
            Input::make('region')
                ->title(__('Ciudad')),
        ];
    }
}
