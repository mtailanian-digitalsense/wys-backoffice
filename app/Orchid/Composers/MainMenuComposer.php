<?php

declare(strict_types=1);

namespace App\Orchid\Composers;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemMenu;
use Orchid\Platform\Menu;

class MainMenuComposer
{
    /**
     * @var Dashboard
     */
    private $dashboard;

    /**
     * MenuComposer constructor.
     *
     * @param Dashboard $dashboard
     */
    public function __construct(Dashboard $dashboard)
    {
        $this->dashboard = $dashboard;
    }

    /**
     * Registering the main menu items.
     */
    public function compose()
    {
        // Profile
        $this->dashboard->menu;

        // Main
        $this->dashboard->menu
            ->add(Menu::MAIN,
                ItemMenu::label('Usuarios Administradores')
                    ->permission('platform.systems.admin')
                    ->icon('icon-user')
                    ->title('Systems')
                    ->route('platform.systems.admin')
            )
            ->add(Menu::MAIN,
                ItemMenu::label('Users')
                    ->permission('platform.systems.users')
                    ->icon('icon-user')
                    ->route('platform.systems.users')
            )
            ->add(Menu::MAIN,
                ItemMenu::label('Roles')
                    ->permission('platform.systems.roles')
                    ->icon('icon-lock')
                    ->route('platform.systems.roles')
            )
            ->add(Menu::MAIN,
                ItemMenu::label('Parametros')
                    
                    ->icon('icon-settings')
                    ->route('platform.systems.parameters')
            )
            ->add(Menu::MAIN,
                ItemMenu::label('Espacios')
                    ->icon('icon-monitor')
                    ->route('platform.modules.spaces')
                    ->title('Módulo M2')
            )
            ->add(Menu::MAIN,
                ItemMenu::label('Zonas')
                    ->icon('icon-circle_thin')
                    ->route('platform.modules.zones')
                    ->title('Módulo Edificios')
            )
            ->add(Menu::MAIN,
                ItemMenu::label('Edificios')
                    ->icon('icon-building')
                    ->route('platform.modules.buildings')
            )
            ->add(Menu::MAIN,
                ItemMenu::label('Diseños')
                    ->icon('icon-plus')
                    ->route('platform.modules.costs.designs')
                    ->title('Módulo Costos')
            )
            ->add(Menu::MAIN,
                ItemMenu::label('Costos')
                    ->icon('icon-plus')
                    ->route('platform.modules.costs')
            )
            ->add(Menu::MAIN,
                ItemMenu::label('Descripciones')
                    ->icon('icon-plus')
                    ->route('platform.modules.costs.descriptions')
            )

        ;
    }
}
