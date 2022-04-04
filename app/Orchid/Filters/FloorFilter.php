<?php

declare(strict_types=1);

namespace App\Orchid\Filters;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Platform\Models\Role;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;

class FloorFilter extends Filter
{
    /**
     * @var array
     */
    public $parameters = [
        'wys_id',
    ];

    /**
     * @return string
     */
    public function name(): string
    {
        return __('Pisos con wys id');
    }

    /**
     * @param Builder $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        return $builder->whereHas('floors', function (Builder $query) {
            $query->where('wys_id', $this->request->get('wys_id'));
        });
    }

    /**
     * @return Field[]
     */
    public function display(): array
    {
        return [
            Input::make('wys_id')
                ->value($this->request->get('role'))
                ->title(__('Wys Id')),
        ];
    }
}
