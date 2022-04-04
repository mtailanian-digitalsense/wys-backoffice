<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Space;

use App\Models\Category;
use App\Models\Space;
use App\Orchid\Listeners\SubcategoryListener;
use App\Wrappers\ContractApiWrapper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Storage;
use Orchid\Platform\Models\Role;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layout;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;
use function GuzzleHttp\Promise\all;

class SpaceCreateScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Creación de espacio';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Crear un espacio';

    /**
     * @var string
     */
    public $permission = 'platform.systems.roles';

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
    public function query(Space $space): array
    {
        $this->exist = $space->exists;

        return [
            'space' => $space,
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
     * @param Category $category
     * @return string[]
     */
    public function asyncSum(Category $category)
    {
        return [
            'space.category_id' => $category->id,
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
                Input::make('space.name')
                    ->type('text')
                    ->max(255)
                    ->required()
                    ->title(__('Name'))
                    ->placeholder(__('Name'))
                    ->help(__('Space display name')),

                Select::make('space.category_id')
                    ->fromModel(Category::class, 'name')
                    ->required()
                    ->empty('No select')
                    ->title(__('Categoría')),

            ])->title('Datos basicos'),
            SubcategoryListener::class,
            Layout::rows([
                Group::make([
                    CheckBox::make('space.active')
                        ->title('Estado del espacio')
                        ->sendTrueOrFalse()
                        ->placeholder('Activo')
                    ,

                    CheckBox::make('space.regular')
                        ->title('Espacio regular')
                        ->disabled()
                        ->sendTrueOrFalse()
                        ->value(true)
                        ->placeholder('Si')
                ]),
            ]),
            Layout::rows([
                Group::make([
                    Picture::make('model_2d')
                        ->required()
                        ->title('Subir modelo 2D')
                        ->targetRelativeUrl(),
                    Picture::make('model_3d')
                        ->required()
                        ->title('Subir modelo 3D')
                        ->targetRelativeUrl()
                ]),
            ])->title('Imagenes'),

            Layout::rows([
                Group::make([
                    Input::make('space.height')
                        ->title('Altura:')
                        ->required()
                        ->min(0)
                        ->placeholder('Ingrese la altura')
                        ->type('number')
                        ->help('Altura del espacio en cms'),

                    Input::make('space.width')
                        ->title('Ancho:')
                        ->required()
                        ->min(0)
                        ->type('number')
                        ->placeholder('Ingrese el ancho')
                        ->help('Ancho del espacio en cms'),
                ]),
            ])->title('Dimensiones'),

            Layout::rows([
                Group::make([
                    Input::make('space.down_gap')
                        ->title('Holgura inferior:')
                        ->required()
                        ->min(0)
                        ->type('number')
                        ->help('Holgura en cms'),

                    Input::make('space.left_gap')
                        ->title('Holgura izquierda:')
                        ->required()
                        ->min(0)
                        ->type('number')
                        ->help('Holgura en cms'),
                ]),
                Group::make([
                    Input::make('space.right_gap')
                        ->title('Holgura derecha:')
                        ->required()
                        ->min(0)
                        ->type('number')
                        ->help('Holgura en cms'),

                    Input::make('space.up_gap')
                        ->title('Holgura superior:')
                        ->required()
                        ->min(0)
                        ->type('number')
                        ->help('Holgura en cms'),
                ]),

            ])->title('Holguras'),
        ];
    }

    /**
     * @param Role $role
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function save(Space $space, Request $request)
    {
        $request->validate([
        ]);

        $model_2d_path = str_replace('/storage/', '', $request->get('model_2d'));
        $file2d = Storage::disk('public')->get($model_2d_path);

        $model_3d_path = str_replace('/storage/', '', $request->get('model_3d'));
        $file3d = Storage::disk('public')->get($model_3d_path);


        $spaceData = $request->get('space');
        $spaceData['model_2d'] = 'data:image/png;base64,'.base64_encode($file2d);
        $spaceData['model_3d'] = 'data:image/png;base64,'.base64_encode($file3d);
        $spaceData = collect($spaceData)->map(function ($value, $key) {
            return $key == 'active' || $key == 'regular' ? [$key => (int)$value] : [$key => $value];
        })->collapse()->forget('category_id')->toArray();

        try {
            $spaceApi = (new ContractApiWrapper)->save_space($spaceData);
            $spaceData['id'] = $spaceApi->id;
            $space->fill($spaceData)->save();
        } catch (Exception $exception) {
            Toast::error('Problema al guardar el espacio');
            return redirect()->route('platform.modules.spaces.create');
        }


        Toast::info(__('Espacio creado correctamente'));
        return redirect()->route('platform.modules.spaces');

    }

}
