<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\AdminUser;

use App\Models\Country;
use Orchid\Platform\Models\Role;
use Orchid\Screen\Exceptions\TypeException;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Password;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class AdminCreateLayout extends Rows
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

            Input::make('user.email')
                ->type('email')
                ->required()
                ->title(__('Correo'))
                ->placeholder(__('Email')),

            Password::make('user.password')
                ->placeholder(__('Enter your password'))
                ->required()
                ->title(__('Password')),

            Password::make('user.password_confirmation')
                ->placeholder(__('Enter your password'))
                ->required()
                ->title(__('Confirm Password')),


            Select::make('user.roles.')
                ->fromModel(Role::class, 'name')
                ->multiple()
                ->required()
                ->title(__('Name role'))
                ->help('Specify which groups this account should belong to'),

        ];
    }
}
