<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Building;

use App\Models\Building;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class BuildingListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'buildings';

    /**
     * @return array
     */
    public function columns(): array
    {
        return [
            TD::set('name', __('Nombre'))
                ->sort()
                ->cantHide()
                ->width('200px')
                ->filter(TD::FILTER_TEXT)
                ->render(function ($building) {
                    return Link::make(strval($building->name))
                        ->route('platform.modules.buildings.edit', $building->id);
                }),

            TD::set('country', __('País'))
                ->cantHide()
                ->render(function ($building) {
                    return $building->zone->region->country->name;
                }),


            TD::set('region', __('Ciudad'))
                ->cantHide()
                ->render(function ($building) {
                    return $building->zone->region->name;
                }),

            TD::set('zone', __('Zona'))
                ->cantHide()
                ->render(function ($building) {
                    return $building->zone->name;
                }),

            TD::set('active', __('Activo'))
                ->cantHide()
                ->render(function ($building) {
                    return CheckBox::make()
                        ->value($building->active)
                        ->disabled();
                }),
            TD::set('modify', 'Opciones')
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (Building $building) {
                    return DropDown::make()
                        ->icon('icon-options-vertical')
                        ->list([

                            Link::make(__('Edit'))
                                ->route('platform.modules.buildings.edit', $building->id)
                                ->icon('icon-pencil'),
                        ]);
                }),
        ];
    }
}
