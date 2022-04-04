<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use Orchid\Platform\Dashboard;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layout;
use Orchid\Screen\Screen;

class PlatformScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Back Office';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Welcome';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'status' => Dashboard::checkUpdate(),
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
            Link::make('Website')
                ->href('http://example.com')
                ->icon('icon-globe-alt'),
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
            Layout::view('partials.welcome'),
        ];
    }
}
