<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Building;

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

class BuildingListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Edificios';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Todos los edificios';

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
            Link::make(__('Add'))
                ->icon('icon-plus')
                ->href(route('platform.modules.buildings.create')),

            ModalToggle::make('Carga masiva')
                ->modal('massiveModal')
                ->method('save')
                ->icon('icon-cloud-upload'),

            Button::make(__('Sincronizar'))
                ->icon('icon-cloud-download')
                ->method('syncSpaces'),
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
            BuildingFiltersLayout::class,
            BuildingListLayout::class,
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
            (new ContractApiWrapper)->save_buildings_file($request->doc);
            Toast::success($request->get('massive', 'Archivo subido correctamente '));
        } catch (Exception $exception) {
            Toast::error($request->get('massive', 'Ocurrio un error al subir el archivo'));
        }
        return back();
    }

    /**
     * @return RedirectResponse
     */
    public function syncSpaces()
    {
        Artisan::call("buildings:cron");
        Toast::info(__('Edificios actualizados'));
        return back();
    }

}
