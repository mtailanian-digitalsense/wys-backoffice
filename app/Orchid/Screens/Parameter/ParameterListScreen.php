<?php

namespace App\Orchid\Screens\Parameter;

use Orchid\Screen\Screen;
use App\Models\Parameter;
use App\Orchid\Layouts\Parameter\ParameterListLayout;

class ParameterListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Parámetros';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Parámetros del sistema';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'parameters' => Parameter::all()
        ];
    }

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function commandBar(): array
    {
        return [
 
        ];
    }

    /**
     * Views.
     *
     * @return Layout[]
     */
    public function layout(): array
    {
        return [
            ParameterListLayout::class,
        ];
    }
}
