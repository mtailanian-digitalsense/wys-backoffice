<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Zone;

use App\Models\Zone;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ZoneListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'zones';

    /**
     * @return array
     */
    public function columns(): array
    {
        return [
            TD::set('id', __('ID'))
                ->sort()
                ->cantHide()
                ->filter(TD::FILTER_TEXT)
                ->render(function (Zone $zone) {
                    return Link::make(strval($zone->id))
                        ->route('platform.modules.zones.edit', $zone->id);
                }),

            TD::set('name', __('Nombre'))
                ->sort()
                ->cantHide()
                ->width('200px')
                ->filter(TD::FILTER_TEXT)
                ->render(function ($space) {
                    return $space->name;
                }),

            TD::set('category', __('País'))
                ->cantHide()
                ->render(function ($zone) {
                    return $zone->region->country->name;
                }),

            TD::set('subcategory', __('Región'))
                ->cantHide()
                ->render(function ($space) {
                    return $space->region->name;
                }),

            TD::set('active', __('Activo'))
                ->cantHide()
                ->render(function ($space) {
                    return CheckBox::make()
                        ->value($space->active)
                        ->disabled();
                }),
            TD::set('modify', 'Opciones')
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (Zone $zone) {
                    return DropDown::make()
                        ->icon('icon-options-vertical')
                        ->list([

                            Link::make(__('Edit'))
                                ->route('platform.modules.zones.edit', $zone->id)
                                ->icon('icon-pencil'),
                        ]);
                }),
        ];
    }
}
