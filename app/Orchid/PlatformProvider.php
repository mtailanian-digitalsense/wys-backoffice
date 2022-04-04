<?php

declare(strict_types=1);

namespace App\Orchid;

use App\Orchid\Composers\MainMenuComposer;
use App\Orchid\Composers\SystemMenuComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;

class PlatformProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @param Dashboard $dashboard
     */
    public function boot(Dashboard $dashboard)
    {
        View::composer('platform::dashboard', MainMenuComposer::class);
        View::composer('platform::systems', SystemMenuComposer::class);

        $dashboard->registerPermissions($this->registerPermissionsSystems());
        $dashboard->registerPermissions($this->registerPermissionsModules());
        $dashboard->registerPermissions($this->registerPermissionsSpaces());

        $dashboard->registerSearch([
            //...Models
        ]);
    }

    /**
     *
     */

    protected function registerPermissionsModules(): ItemPermission
    {
        return ItemPermission::group(__('Modules'))
            ->addPermission('monitor', __('Access to the system monitor'));
    }

    /**
     *
     */

    protected function registerPermissionsSpaces(): ItemPermission
    {
        return ItemPermission::group(__('ContractApiWrapper'))
            ->addPermission('platform.modules.spaces', __('Espacios'));
    }

    /**
     * @return ItemPermission
     */
    protected function registerPermissionsSystems(): ItemPermission
    {
        return ItemPermission::group(__('Systems'))
            ->addPermission('platform.systems.roles', __('Roles'))
            ->addPermission('platform.systems.admin', __('Administradores'))
            ->addPermission('platform.systems.users', __('Users'));
    }
}
