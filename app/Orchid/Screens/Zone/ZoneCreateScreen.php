<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Zone;

use App\Models\Country;
use App\Models\Region;
use App\Models\Space;
use App\Models\Zone;
use App\Orchid\Listeners\RegionListener;
use App\Wrappers\ContractApiWrapper;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layout;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class ZoneCreateScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Creación de zona';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Crear una zona';

    /**
     * @var string
     */
    public $permission = 'platform.systems.users';

    /**
     * @var bool
     */
    private $exist = false;

    /**
     * Query data.
     *
     * @param Space $space
     *
     * @return array
     */
    public function query(Zone $zone): array
    {
        $this->exist = $zone->exists;

        return [
            'zone' => $zone,
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
                    ->fromModel(Country::class, 'name')
                    ->required()
                    ->empty('No select')
                    ->title(__('País')),

            ])->title('Datos basicos'),
            RegionListener::class,
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

        try {
            $zoneApi = (new ContractApiWrapper)->save_zone($apiData);
            $data['id'] = $zoneApi->id;
            $zone->fill($data)->save();
        } catch (Exception $exception) {
            Toast::error('Problema al guardar la zona');
            return redirect()->route('platform.modules.zones.create');
        }

        Toast::info(__('Zona creada correctamente'));
        return redirect()->route('platform.modules.zones');

    }

}
