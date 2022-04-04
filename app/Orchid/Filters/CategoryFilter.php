<?php

namespace App\Orchid\Filters;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Select;

class CategoryFilter extends Filter
{
    /**
     * @var array
     */
    public $parameters = [
        'category'
    ];

    /**
     * @return string
     */
    public function name(): string
    {
        return 'Categoría';
    }

    /**
     * @param Builder $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        return $builder->whereHas('subcategory.category', function (Builder $query) {
            $query->where('name', $this->request->get('category'));
        });
    }

    /**
     * @return Field[]
     */
    public function display(): array
    {
        return [
            Select::make('category')
                ->fromModel(Category::class, 'name', 'name')
                ->empty()
                ->value($this->request->get('category'))
                ->title(__('Categoría')),
        ];
    }
}
