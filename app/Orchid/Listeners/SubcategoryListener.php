<?php

namespace App\Orchid\Listeners;

use App\Models\Category;
use App\Models\Subcategory;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layout;
use Orchid\Screen\Layouts\Listener;

class SubcategoryListener extends Listener
{
    /**
     * List of field names for which values will be listened.
     *
     * @var string[]
     */
    protected $targets = ['space.category_id'];

    /**
     * What screen method should be called
     * as a source for an asynchronous request.
     *
     * The name of the method must
     * begin with the prefix "async"
     *
     * @var string
     */
    protected $asyncMethod = 'asyncSum';


    /**
     * @return Layout[]
     */
    protected function layouts(): array
    {
        return [
            Layout::rows([
                Select::make('space.subcategory_id')
                    ->fromQuery(Subcategory::where('category_id', '=', $this->query->get('space.category_id')), 'name')
                    ->required()
                    ->title(__('Subcategoría')),
            ]),
        ];
    }
}
