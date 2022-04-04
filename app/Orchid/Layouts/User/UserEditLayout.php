<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\User;

use App\Models\Country;
use Orchid\Platform\Models\Role;
use Orchid\Screen\Exceptions\TypeException;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class UserEditLayout extends Rows
{
    /**
     * Views.
     *
     * @return array
     *@throws \Throwable|TypeException
     *
     */
    public function fields(): array
    {
        return [
            Input::make('user.name')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('Name'))
                ->placeholder(__('Name')),

            Input::make('user.last_name')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('Apellido'))
                ->placeholder(__('Last name')),

            Select::make('user.country_id')
                ->fromModel(Country::class, 'name')
                ->required()
                ->title(__('Ciudad')),

            Input::make('user.email')
                ->type('email')
                ->required()
                ->title(__('Correo'))
                ->placeholder(__('Email')),

            CheckBox::make('confirmed')
                ->title('Estado usuario')
                ->disabled()
                ->placeholder('Usuario confirmado vía correo electrónico'),

            CheckBox::make('user.active')
                ->sendTrueOrFalse()
                ->placeholder('Usuario activo'),

        ];
    }
}
