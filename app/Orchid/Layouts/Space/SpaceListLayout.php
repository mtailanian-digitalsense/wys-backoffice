<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Space;

use App\Models\Space;
use App\Models\User;
use App\Orchid\Filters\SubcategoryFilter;
use App\Wrappers\ContractApiWrapper;
use Orchid\Platform\Models\Role;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class SpaceListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'spaces';

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
                ->render(function (Space $space) {
                    return Link::make(strval($space->id))
                        ->route('platform.modules.spaces.edit', $space->id);
                }),

            TD::set('name', __('Nombre'))
                ->sort()
                ->cantHide()
                ->width('200px')
                ->filter(TD::FILTER_TEXT)
                ->render(function ($space) {
                    return $space->name;
                }),

            TD::set('category', __('Categoría'))
                ->cantHide()
                ->width('100px')
                ->render(function ($space) {
                    return $space->subcategory->category->name;
                }),

            TD::set('subcategory', __('Subcategoría'))
                ->cantHide()
                ->width('100px')
                ->render(function ($space) {
                    return $space->subcategory->name;
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
                ->render(function (Space $space) {
                    return DropDown::make()
                        ->icon('icon-options-vertical')
                        ->list([

                            Link::make(__('Edit'))
                                ->route('platform.modules.spaces.edit', $space->id)
                                ->icon('icon-pencil'),
                        ]);
                }),
        ];
    }
}
