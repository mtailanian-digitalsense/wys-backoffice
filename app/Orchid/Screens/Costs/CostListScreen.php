<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Costs;

use App\Models\Building;
use App\Orchid\Layouts\Building\BuildingFiltersLayout;
use App\Orchid\Layouts\Building\BuildingListLayout;
use App\Orchid\Layouts\Zone\ZoneFiltersLayout;
use App\Wrappers\ContractApiWrapper;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layout;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class CostListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Costos';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Carga de costos';

    /**
     * @var string
     */
    public $permission = 'platform.systems.users';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'buildings' => Building::with('zone')
                ->filters()
                ->filtersApplySelection(BuildingFiltersLayout::class)
                ->defaultSort('id', 'desc')->paginate(),
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
            ModalToggle::make('Carga masiva')
                ->modal('massiveModal')
                ->method('save')
                ->icon('icon-cloud-upload'),
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
            Layout::modal('massiveModal', [
                Layout::rows([
                    Input::make('doc')->type('file')
                ]),
            ])->title('Carga masiva de edificios'),
        ];
    }


    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function save(Request $request)
    {
        try {
            (new ContractApiWrapper)->save_cost_file($request->doc);
            Toast::success($request->get('massive', 'Archivo subido correctamente '));
        } catch (Exception $exception) {
            Toast::error($request->get('massive', 'Ocurrio un error al subir el archivo'));
        }
        return back();
    }
}
