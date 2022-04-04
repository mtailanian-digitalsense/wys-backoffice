<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Space;

use App\Orchid\Listeners\SubcategoryEditListener;
use Exception;
use App\Models\Category;
use App\Models\Space;
use App\Wrappers\ContractApiWrapper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
use Orchid\Support\Color;
use Orchid\Support\Facades\Toast;

class SpaceEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Editar espacio';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Información del espacio';

    /**
     * @var string
     */
    public $permission = 'platform.systems.roles';

    /**
     * @var bool
     */

    private $category = '';


    /**
     * Query data.
     *
     * @param Space $space
     *
     * @return array
     */
    public function query(Space $space): array
    {
        $images = (new ContractApiWrapper)->get_images($space->id);
        $this->category = $space->subcategory->category->id;
        return [
            'category_id' => $this->category,
            'model_2d' => str_replace("data:image/png;base64,", "", $images['model_2d']),
            'model_3d' => str_replace("data:image/png;base64,", "", $images['model_3d']),
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
                    ->fromQuery(Category::orderByRaw(DB::raw("FIELD(id, '$this->category') desc")), 'name')
                    ->title(__('Nueva categoría')),

            ])->title('Datos basicos'),
            SubcategoryEditListener::class,

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
            Layout::view('models'),
            Layout::rows([
                Group::make([
                    Picture::make('model_2d_new')
                        ->title('Subir modelo 2D')
                        ->targetRelativeUrl(),
                    Picture::make('model_3d_new')
                        ->title('Subir modelo 3D')
                        ->targetRelativeUrl()
                ]),
            ]),

            Layout::rows([
                Group::make([
                    Input::make('space.height')
                        ->title('Altura:')
                        ->placeholder('Ingrese la altura')
                        ->type('number')
                        ->help('Altura del espacio en cms'),

                    Input::make('space.width')
                        ->title('Ancho:')
                        ->type('number')
                        ->placeholder('Ingrese el ancho')
                        ->help('Ancho del espacio en cms'),
                ]),
            ])->title('Dimensiones'),

            Layout::rows([
                Group::make([
                    Input::make('space.down_gap')
                        ->title('Holgura inferior:')
                        ->type('number')
                        ->help('Holgura en cms'),

                    Input::make('space.left_gap')
                        ->title('Holgura izquierda:')
                        ->type('number')
                        ->help('Holgura en cms'),
                ]),
                Group::make([
                    Input::make('space.right_gap')
                        ->title('Holgura derecha:')
                        ->type('number')
                        ->help('Holgura en cms'),

                    Input::make('space.up_gap')
                        ->title('Holgura superior:')
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
        $spaceData = $request->get('space');
        $spaceData['regular'] = 1;
        $spaceData = collect($spaceData)->map(function ($value, $key) {
            return $key == 'active' || $key == 'regular' ? [$key => (int)$value] : [$key => $value];
        })->collapse()->forget('category_id')->toArray();

        if ($request->get('model_2d_new') != null) {
            $spaceData['model_2d'] = $this->generate_base64_image($request->get('model_2d_new'));
        }

        if ($request->get('model_3d_new') != null) {
            $spaceData['model_3d'] = $this->generate_base64_image($request->get('model_3d_new'));
        }

        try {
            (new ContractApiWrapper)->update_space($space->id, $spaceData);
        } catch (Exception $exception) {
            Toast::error('Problema al guardar el espacio');
            return redirect()->route('platform.modules.spaces.create');
        }

        $space->fill($spaceData)->save();

        Toast::info(__('Espacio editado correctamente'));
        return redirect()->route('platform.modules.spaces');
    }

    private function generate_base64_image($model_absolute_path)
    {
        $model_path = str_replace('/storage/', '', $model_absolute_path);
        return base64_encode(Storage::disk('public')->get($model_path));
    }

}
