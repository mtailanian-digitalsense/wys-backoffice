<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Zone;

use App\Models\Space;
use App\Models\Zone;
use App\Orchid\Layouts\Space\SpaceFiltersLayout;
use App\Orchid\Layouts\Space\SpaceListLayout;
use App\Orchid\Layouts\Zone\ZoneFiltersLayout;
use App\Orchid\Layouts\Zone\ZoneListLayout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layout;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class ZoneListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Zona';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Todos las zonas';

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
            'zones' => Zone::with('region', 'region.country')
                ->filters()
                ->filtersApplySelection(ZoneFiltersLayout::class)
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
                ->href(route('platform.modules.zones.create')),
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
            ZoneFiltersLayout::class,
            ZoneListLayout::class,
        ];
    }

    /**
     * @return RedirectResponse
     */
    public function syncSpaces()
    {
        Artisan::call("zones:cron");
        Toast::info(__('Zonas actualizadas'));
        return back();
    }


}
