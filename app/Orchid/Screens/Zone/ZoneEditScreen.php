<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Zone;

use App\Models\Country;
use App\Models\Region;
use App\Models\Space;
use App\Models\Zone;
use App\Orchid\Listeners\RegionEditListener;
use Exception;
use App\Wrappers\ContractApiWrapper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layout;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class ZoneEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Edición de una zona';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Editar una zona';

    /**
     * @var string
     */
    public $permission = 'platform.systems.users';

    /**
     * @var bool
     */
    private $country;

    /**
     * Query data.
     *
     * @param Space $space
     *
     * @return array
     */
    public function query(Zone $zone): array
    {
        $this->country = $zone->region->country->id;

        return [
            'zone' => $zone,
            'country_id' => $this->country,
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
     * @param Country $country
     * @return string[]
     */
    public function asyncRegion(Country $country)
    {
        return [
            'zone.country_id' => $country->id,
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
                Input::make('zone.name')
                    ->type('text')
                    ->max(255)
                    ->required()
                    ->title(__('Name'))
                    ->placeholder(__('Name'))
                    ->help(__('Nombre para mostrar de la zona')),

                Select::make('zone.mun_agility')
                    ->options([
                    'low'   => 'Bajo',
                    'normal' => 'Normal',
                    'high' => 'Alto',
                    ])->title('Agilidad Municipal')
                    ->required(),

                Select::make('zone.country_id')
                    ->fromQuery(Country::orderByRaw(DB::raw("FIELD(id, '$this->country') desc")), 'name')
                    ->required()
                    ->title(__('País')),


                CheckBox::make('zone.active')
                    ->title('Estado de ls zona')
                    ->sendTrueOrFalse()
                    ->placeholder('Activo')


            ])->title('Datos basicos'),
            RegionEditListener::class,
        ];
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function save(Zone $zone, Request $request)
    {
        $data = $request->get('zone');
        $apiData['name'] = $data['name'];
        $apiData['country'] = ucfirst(Country::all()->where('id', $data['country_id'])->first()->name);
        $apiData['region'] = ucfirst(Region::all()->where('id', $data['region_id'])->first()->name);
        $apiData['mun_agility'] = $data['mun_agility'];
        $apiData['active'] = true;
        try {
            $zoneApi = (new ContractApiWrapper)->update_zone($zone->id, $apiData);
            $data['id'] = $zoneApi->id;
            $zone->fill($data)->save();
        } catch (Exception $exception) {
            Toast::error('Problema al guardar la zona');
            return redirect()->route('platform.modules.zones.edit');
        }

        Toast::info(__('Zona guardada correctamente'));
        return redirect()->route('platform.modules.zones');

    }

}
