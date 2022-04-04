<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Space;

use App\Models\Space;
use App\Orchid\Layouts\Space\SpaceFiltersLayout;
use App\Orchid\Layouts\Space\SpaceListLayout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layout;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class SpaceListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Espacio';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Todos los espacios';

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
            'spaces' => Space::with('subcategory', 'subcategory.category')
                ->filters()
                ->filtersApplySelection(SpaceFiltersLayout::class)
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
                ->href(route('platform.modules.spaces.create')),
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
            SpaceFiltersLayout::class,
            SpaceListLayout::class,
        ];
    }

    /**
     * @return RedirectResponse
     */
    public function syncSpaces(){
        Artisan::call("spaces:cron");
        Toast::info(__('Espacios actualizados'));
        return back();
    }


}
