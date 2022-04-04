<?php

declare(strict_types=1);

namespace App\Orchid\Filters;

use App\Models\Subcategory;
use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Select;

class SubcategoryFilter extends Filter
{
    /**
     * @var array
     */
    public $parameters = [
        'subcategory',
    ];

    /**
     * @return string
     */
    public function name(): string
    {
        return __('Subcategoría');
    }

    /**
     * @param Builder $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        return $builder->whereHas('subcategory', function (Builder $query) {
            $query->where('name', $this->request->get('subcategory'));
        });
    }

    /**
     * @return Field[]
     */
    public function display(): array
    {
        return [
            Select::make('subcategory')
                ->fromModel(Subcategory::class, 'name', 'name')
                ->empty()
                ->value($this->request->get('subcategory'))
                ->title(__('Subcategoría')),
        ];
    }
}
