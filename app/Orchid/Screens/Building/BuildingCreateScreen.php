<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Building;

use App\Models\Building;
use App\Models\Zone;
use App\Wrappers\ContractApiWrapper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Storage;
use Orchid\Attachment\Models\Attachment;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Matrix;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Layout;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class BuildingCreateScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Creación de edificio';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Crear un edificio';

    /**
     * @var string
     */
    public $permission = 'platform.systems.users';

    /**
     * Query data.
     *
     * @param Building $building
     *
     * @return array
     */
    public function query(Building $building): array
    {

        return [
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
            Layout::tabs([
                'Información Edificio' => [
                    Layout::rows([

                        Input::make('building.name')
                            ->title('Nombre')
                            ->placeholder('Ingrese el nombre del edificio')
                            ->required(),

                        Field::group([
                            Input::make('building.building_year')
                                ->type('number')
                                ->required()
                                ->min(0)
                                ->title('Año de construcción'),

                            Input::make('building.category')
                                ->required()
                                ->title('Categoría'),
                        ]),

                        Field::group([
                            Input::make('building.parking_number')
                                ->type('number')
                                ->min(0)
                                ->required()
                                ->title('Número de estacionamiento'),

                            Select::make('building.adm_agility')
                                ->options([
                                'low'   => 'Bajo',
                                'normal' => 'Normal',
                                'high' => 'Alto',
                                ])->title('Agilidad de Administración')
                                ->required(),

                            Input::make('building.total_floors')
                                ->type('number')
                                ->min(0)
                                ->required()
                                ->title('Pisos totales'),
                                
                        ]),

                        CheckBox::make('building.planta_tipo')
                            ->title('Planta Tipo')
                            ->sendTrueOrFalse(),

                    ])->title('Datos basicos'),

                    Layout::rows([

                        Field::group([
                            Input::make('building.street')
                                ->type('text')
                                ->title('Calle')
                                ->placeholder('Ingrese la calle')
                                ->required(),

                            Input::make('building.address_number')
                                ->title('Número')
                                ->min(1)
                                ->placeholder('Ingrese el número')
                                ->required(),
                        ]),

                        Field::group([

                            Select::make('building.zone_id')
                                ->fromQuery(Zone::where('active', '!=', '0'), 'name')
                                ->required()
                                ->title('Zona'),

                            Input::make('building.gps_location')
                                ->required()
                                ->title('Ubicación GPS'),
                        ]),

                    ])->title('Ubicación'),

                    Layout::rows([
                        Field::group([
                            Input::make('building.infrastructure_lvl')
                                ->type('number')
                                ->placeholder('Entre 1 y 10')
                                ->value(5)
                                ->min(1)
                                ->max(10)
                                ->required()
                                ->title('Infraestructura'),

                            Input::make('building.parking_lvl')
                                ->type('number')
                                ->placeholder('Entre 1 y 10')
                                ->value(5)
                                ->min(1)
                                ->max(10)
                                ->required()
                                ->title('Estacionamiento'),

                            Input::make('building.public_transport_lvl')
                                ->type('number')
                                ->placeholder('Entre 1 y 10')
                                ->value(5)
                                ->min(1)
                                ->max(10)
                                ->required()
                                ->title('Transporte Público'),
                        ]),

                        Field::group([

                            Input::make('building.services_lvl')
                                ->type('number')
                                ->placeholder('Entre 1 y 10')
                                ->value(5)
                                ->min(1)
                                ->max(10)
                                ->required()
                                ->title('Servicios'),

                            Input::make('building.sustainability_lvl')
                                ->type('number')
                                ->placeholder('Entre 1 y 10')
                                ->value(5)
                                ->min(1)
                                ->max(10)
                                ->required()
                                ->title('Sustentabilidad'),

                            Input::make('building.view_lvl')
                                ->type('number')
                                ->placeholder('Entre 1 y 10')
                                ->value(5)
                                ->min(1)
                                ->max(10)
                                ->required()
                                ->title('Vista'),
                        ]),

                        Input::make('building.security_lvl')
                            ->type('number')
                            ->placeholder('Entre 1 y 10')
                            ->value(5)
                            ->min(1)
                            ->max(10)
                            ->required()
                            ->title('Seguridad'),


                    ])->title('Niveles'),
                ],
                'Imagenes' => [
                    Layout::rows([
                        Upload::make('images')
                            ->maxFiles(30)
                    ]),
                ],
                'Arrendatarios' => [
                    Layout::rows([
                        Matrix::make('renters')
                            ->title('Arrendatarios')
                            ->columns(['Nombre' => 'name',])
                            ->fields([
                                'name' => TextArea::make()->required(),
                            ]),
                    ])->title('Lista de arrendatarios para este edificio'),
                ],
                'Pisos' => [
                    Layout::rows([
                        Matrix::make('floors')
                            ->title('Pisos')
                            ->columns([
                                'Numero de elevadores' => 'elevators_number',
                                'Imagen' => 'image_link',
                                'M2' => 'm2',
                                'Valor renta' => 'rent_value',
                                'wys id' => 'wys_id',
                            ])
                            ->fields([
                                'elevators_number' => Input::make()->type('number')->required(),
                                'image_link' => Picture::make()->targetRelativeUrl()->required(),
                                'm2' => Input::make()->type('number')->required(),
                                'rent_value' => Input::make()->type('number')->required(),
                            ]),
                    ])->title('Lista de pisos para este edificio'),
                ]
            ]),

        ];
    }

    /**
     * @param Building $building
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function save(Building $building, Request $request)
    {
        $data = $request->get('building');
        $images = $request->get('images');
        $renters = $request->get('renters');
        $floors = $request->floors;

        //building saving
        try {
            $apiResponse = (new ContractApiWrapper)->save_building($data);
            $data['id'] = $apiResponse->id;
            $building->fill($data)->save();
        } catch (Exception $exception) {
            Toast::error('Problema al guardar el edificio');
            return redirect()->route('platform.modules.buildings.create');
        }

        try {
            if ($images != null)
                $this->save_building_images($apiResponse->id, $images);
            if ($floors != null)
                $this->save_floors($apiResponse->id, $floors);
            if ($renters != null)
                $this->save_renters($apiResponse->id, $renters);
        } catch (Exception $exception) {
            Toast::error('Ocurrio un problema al guardar imagenes, pisos o arrendatarios');
            return redirect()->route('platform.modules.buildings.create');
        }
        Toast::info(__('Edificio creado correctamente'));
        return redirect()->route('platform.modules.buildings');

    }

    private function save_building_images($buildingId, $images)
    {
        $collection = collect([]);

        foreach ($images as $image) {
            $file = Attachment::where('id', $image)->firstOrFail();
            $filePath = Storage::disk('public')->path(str_replace('/storage/', '', $file->relativeUrl));
            $content = fopen($filePath, 'r');
            $response = (new ContractApiWrapper)->save_image($content);
            $file->delete();
            $collection->push(['link' => $response->url]);
        }

        (new ContractApiWrapper)->save_building_images($buildingId, $collection);
    }

    private function save_renters($buildingId, $renters)
    {
        foreach ($renters as $renter) {
            (new ContractApiWrapper)->save_renter($buildingId, $renter);
        }
    }

    private function save_floors($buildingId, $floors)
    {
        $api = (new ContractApiWrapper);
        foreach ($floors as $floor) {
            $file = Storage::disk('public')->path(str_replace('/storage/', '', $floor['image_link']));
            $content = fopen($file, 'r');
            $response = $api->save_image($content);
            $floor['image_link'] = $response->url;
            $api->save_floor($buildingId, $floor);
        }
    }
}
