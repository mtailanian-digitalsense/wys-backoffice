<?php

declare(strict_types=1);

namespace App\Orchid\Filters;

use App\Models\Subcategory;
use App\Models\Zone;
use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Select;

class ZoneFilter extends Filter
{
    /**
     * @var array
     */
    public $parameters = [
        'zone',
    ];

    /**
     * @return string
     */
    public function name(): string
    {
        return __('Zona');
    }

    /**
     * @param Builder $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        return $builder->whereHas('zone', function (Builder $query) {
            $query->where('name', $this->request->get('zone'));
        });
    }

    /**
     * @return Field[]
     */
    public function display(): array
    {
        return [
            Select::make('zone')
                ->fromModel(Zone::class, 'name', 'name')
                ->empty()
                ->value($this->request->get('zone'))
                ->title(__('Zona')),
        ];
    }
}
