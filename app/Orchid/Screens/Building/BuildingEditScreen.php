<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Building;

use App\Models\Building;
use App\Models\Zone;
use App\Wrappers\ContractApiWrapper;
use http\Exception\UnexpectedValueException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Storage;
use Orchid\Attachment\Models\Attachment;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Matrix;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Switcher;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Layout;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Toast;

class BuildingEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Edición de edificio';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Editar un edificio';

    /**
     * @var string
     */
    public $permission = 'platform.systems.users';

    /**
     * Query data.
     *
     * @param Building $building
     * @return array
     */

    public function query(Building $building): array
    {
        $apiBuilding = (new ContractApiWrapper)->get_building($building->id);
        $renters_id = collect([]);
        $floors_id = collect([]);
        $floors = collect([]);

        // Current renters id
        foreach ($apiBuilding['renters'] as $renter) {
            $renters_id->push($renter['id']);
        }

        // Current floors id
        foreach ($apiBuilding['floors'] as $floor) {
            if ($floor['active']) {
                $floors_id->push($floor['id']);
                $floors->push($floor);
            }
        }

        //dd($floors[0]['image_link']);
        return [
            "floors_id" => json_encode($floors_id),
            "renters_id" => json_encode($renters_id),
            "building" => $building,
            "building_images" => $apiBuilding['building_images'],
            "renters" => $apiBuilding['renters'],
            "floors" => $floors,
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
                        Input::make('renters_id')
                            ->hidden(true),
                        Input::make('floors_id')
                            ->hidden(true),
                        Input::make('building.name')
                            ->title('Nombre')
                            ->placeholder('Ingrese el nombre del edificio')
                            ->required(),

                        Field::group([
                            Input::make('building.building_year')
                                ->type('number')
                                ->required()
                                ->title('Año de construcción'),

                            Input::make('building.category')
                                ->required()
                                ->title('Categoría'),
                        ]),

                        Field::group([
                            Input::make('building.parking_number')
                                ->type('number')
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
                                ->title('Calle')
                                ->placeholder('Ingrese la calle')
                                ->required(),

                            Input::make('building.address_number')
                                ->title('Número')
                                ->placeholder('Ingrese el número')
                                ->required(),
                        ]),

                        Field::group([

                            Select::make('building.zone_id')
                                ->fromModel(Zone::class, 'name')
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
                    Layout::table('building_images', [
                        TD::set('link', 'Imagenes')
                            ->width('150')
                            ->render(function ($image) {
                                // Better use view('path')
                                return "<img src='{$image['link']}'
                              alt='Edificio'
                              class='mw-100 d-block img-fluid'>";
                            }),
                        TD::set('delete', 'Eliminar')
                            ->width('150')
                            ->render(function ($image) {
                                // Better use view('path')
                                return CheckBox::make('delete_' . rand())
                                    ->sendTrueOrFalse();
                            })
                    ]),
                    Layout::rows([
                        Upload::make('images')
                            ->maxFiles(30)
                    ])->title('Subir nuevas imagenes'),
                ],
                'Arrendatarios' => [
                    Layout::rows([
                        Matrix::make('renters')
                            ->title('Arrendatarios')
                            ->columns(['ID' => 'id', 'Nombre' => 'name',])
                            ->fields([
                                'id' => Input::make()
                                    ->readonly()
                                    ->required(),
                                'name' => TextArea::make()
                                    ->required(),
                            ]),
                    ])->title('Lista de arrendatarios para este edificio'),
                ],
                'Pisos' => [
                    Layout::rows([
                        Matrix::make('floors')
                            ->title('Pisos')
                            ->columns([
                                'ID' => 'id',
                                'Numero de elevadores' => 'elevators_number',
                                'Imagen' => 'image_link',
                                'M2' => 'm2',
                                'Valor renta' => 'rent_value',
                                'wys id' => 'wys_id',
                            ])
                            ->fields([
                                'id' => Input::make()
                                    ->readonly()
                                    ->required(),
                                'elevators_number' => Input::make()->type('number')->required(),
                                'image_link' => Picture::make()->targetRelativeUrl(),
                                'm2' => Input::make()->type('number')->required(),
                                'rent_value' => Input::make()->type('number')->required(),
                            ]),
                    ])->title('Lista de pisos para este edificio'),
                ],
            ]),
            Layout::modal('oneAsyncModal', [
                Layout::rows([

                ]),
            ])->async('asyncGetUser'),
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
        $api = new ContractApiWrapper;

        $newImages = $request->get('images');
        $renters = $request->get('renters');
        $floors = $request->floors;
        $renters_id = collect(json_decode($request->get('renters_id')));
        $floors_id = collect(json_decode($request->get('floors_id')));

        $data = $request->get('building');

        try {
            $apiResponse = $api->update_building($building->id, $data);
            $data['id'] = $apiResponse->id;
            $building->fill($data)->save();

        } catch (Exception $exception) {
            Toast::error('Problema al editar el edificio');
            return redirect()->route('platform.modules.buildings');
        }

        //Renters
        $this->update_renters($building->id, $renters, $renters_id);

        // Floors
        try {
            $this->save_floors($building->id, $floors, $floors_id);
        } catch (\Exception $e) {
            Toast::error('Error al procesar el edificio, intententelo más tarde.');
            return redirect()->route('platform.modules.buildings');
        }

        // Images
        //TODO: this code needs to be refactored
        foreach ($this->request->all() as $key => $value) {
            $exp_key = explode('_', $key);
            if ($exp_key[0] == 'delete') {
                $arr_result[] = $value;
            }
        }

        if (isset($arr_result)) {
            try {
                $currentImages = $api->get_building_images($building->id);
            } catch (\Exception $e) {
                Toast::error('Error al procesar el edificio, intententelo más tarde.');
                return redirect()->route('platform.modules.buildings');
            }
            $finalImages = collect([]);
            foreach ($arr_result as $key => $value) {
                if (!$value) {
                    $finalImages->push(['link' => $currentImages[$key]->link]);
                }
            }
            try {
                $this->save_building_images($apiResponse->id, $newImages, $finalImages);
            } catch (\Exception $e) {
                Toast::error('Error al procesar el edificio, intententelo más tarde.');
                return redirect()->route('platform.modules.buildings');
            }
        }

        Toast::info(__('Edificio editado correctamente'));
        return redirect()->route('platform.modules.buildings');
    }


    /**
     * @param $buildingId
     * @param $renters
     * @param $renters_id
     */
    private function update_renters($buildingId, $renters, $renters_id)
    {
        $api = new ContractApiWrapper;
        $actualRentersId = collect([]);
        if ($renters != null)
            foreach ($renters as $renter) {
                if ($renter['id'] != null) {
                    $api->update_renter($buildingId, $renter['id'], ['name' => $renter['name']]);
                    $actualRentersId->push($renter['id']);
                } else {
                    $api->save_renter($buildingId, ['name' => $renter['name']]);
                }
            }

        // Send deleted items to the API
        $toBeDeleted = $renters_id->diff($actualRentersId);
        foreach ($toBeDeleted as $renter) {
            $api->delete_renter($buildingId, $renter);
        }
    }

    /**
     * @param $buildingId
     * @param $floors
     * @param $floors_id
     */
    private function save_floors($buildingId, $floors, $floors_id)
    {
        $api = (new ContractApiWrapper);
        $actualFloorsId = collect([]);
        if ($floors != null)
            foreach ($floors as $floor) {
                $floor_payload = [
                    'elevators_number' => $floor['elevators_number'],
                    'image_link' => $floor['image_link'],
                    'm2' => $floor['m2'],
                    'rent_value' => $floor['rent_value'],
                    'wys_id' => $floor['wys_id'],
                ];
                if ($floor['id'] != null) {
                    if (!str_contains($floor_payload['image_link'], 'wysdev.ac3eplatforms.com')) {
                        $file = Storage::disk('public')->path(str_replace('/storage/', '', $floor['image_link']));
                        $content = fopen($file, 'r');
                        $imageLink = $api->save_image($content);
                        $floor_payload['image_link'] = $imageLink->url;
                    }
                    $api->update_floor($buildingId, $floor['id'], $floor_payload);
                    $actualFloorsId->push($floor['id']);
                } else {
                    $file = Storage::disk('public')->path(str_replace('/storage/', '', $floor['image_link']));
                    $content = fopen($file, 'r');
                    $imageLink = $api->save_image($content);
                    $floor_payload['image_link'] = $imageLink->url;
                    $api->save_floor($buildingId, $floor_payload);
                }
            }

        // Send deleted items to the API
        $toBeDeleted = collect($floors_id)->diff($actualFloorsId);
        foreach ($toBeDeleted as $floor) {
            $api->delete_floor($buildingId, $floor);
        }
    }

    /**
     * @param $buildingId
     * @param $newImages
     * @param $currentImages
     */
    private function save_building_images($buildingId, $newImages, $currentImages)
    {
        if ($newImages)
            foreach ($newImages as $newImage) {
                $file = Attachment::where('id', $newImage)->firstOrFail();
                $filePath = Storage::disk('public')->path(str_replace('/storage/', '', $file->relativeUrl));
                $content = fopen($filePath, 'r');
                $response = (new ContractApiWrapper)->save_image($content);
                $file->delete();
                $currentImages->push(['link' => $response->url]);
            }
        try {
            (new ContractApiWrapper)->save_building_images($buildingId, $currentImages);
        } catch (Exception $exception) {
            Toast::error('El edificio debe tener al menos una imagen');
        }
    }

}
