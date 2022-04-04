<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Parameter;

use Orchid\Screen\Screen;
use App\Models\Parameter;
use Orchid\Screen\Layout;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\Button;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Toast;
use Exception;

class ParameterEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Edicion de los parámetros';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Editar parámetros';

    /**
     * Query data.
     *
     * @param Parameter $parameter
     * @return array
     */
    public function query(Parameter $parameter): array
    {
        return [
            'parameter' => $parameter,
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
            Button::make(__('Save'))
                ->icon('icon-check')
                ->method('save'),
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
            Layout::rows([
                Input::make('parameter.email')
                        ->title('Correo')
                        ->placeholder('Ingrese el correo')
                        ->required(),
            ])->title('Parámetros'),
        ];
    }

    /**
     * @param Parameter $parameter
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function save(Parameter $parameter, Request $request)
    {
        try {
            Parameter::where("id", $parameter->id)->update([
                "email" => $request['parameter.email']
            ]);
            Toast::success('Cambios realizados');
            return redirect()->route('platform.systems.parameters');

        } catch (Exception $exception) {
            Toast::error('Problema al editar los parámetros');
            return redirect()->route('platform.systems.parameters');
        }
    }
}
