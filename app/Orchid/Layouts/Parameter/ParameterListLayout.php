<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Parameter;

use App\Models\Parameter;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ParameterListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'parameters';

    /**
     * @return array
     */
    public function columns(): array
    {
        return [
            TD::set('email', __('Correo'))
                ->sort()
                ->cantHide()
                ->width('200px')
                ->filter(TD::FILTER_TEXT)
                ->render(function ($parameter) {
                    return Link::make(strval($parameter->email))
                        ->route('platform.systems.parameters.edit', $parameter->id);
                }),

            TD::set('modify', 'Opciones')
                ->width('100px')
                ->render(function (Parameter $parameter) {
                    return DropDown::make()
                        ->icon('icon-options-vertical')
                        ->list([

                            Link::make(__('Edit'))
                                ->route('platform.systems.parameters.edit', $parameter->id)
                                ->icon('icon-pencil'),
                        ]);
                }),
        ];
    }
}
