<?php

namespace App\Orchid\Listeners;

use App\Models\Region;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layout;
use Orchid\Screen\Layouts\Listener;

class RegionListener extends Listener
{
    /**
     * List of field names for which values will be listened.
     *
     * @var string[]
     */
    protected $targets = ['zone.country_id'];

    /**
     * What screen method should be called
     * as a source for an asynchronous request.
     *
     * The name of the method must
     * begin with the prefix "async"
     *
     * @var string
     */
    protected $asyncMethod = 'asyncRegion';


    /**
     * @return Layout[]
     */
    protected function layouts(): array
    {
        return [
            Layout::rows([
                Select::make('zone.region_id')
                    ->fromQuery(Region::where('country_id', '=', $this->query->get('zone.country_id')), 'name')
                    ->required()
                    ->title(__('Ciudad')),
            ]),
        ];
    }
}
