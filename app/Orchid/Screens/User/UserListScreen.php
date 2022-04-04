<?php

declare(strict_types=1);

namespace App\Orchid\Screens\User;

use App\Models\Country;
use App\Models\Options;
use App\Orchid\Layouts\User\UserEditLayout;
use App\Orchid\Layouts\User\UserFiltersLayout;
use App\Orchid\Layouts\User\UserListLayout;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\User;
use League\Csv\CannotInsertRecord;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Switcher;
use Orchid\Screen\Layout;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class UserListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'User';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'All registered users';

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
        //Options load
        $options = [];
        foreach (Options::all() as $option) {
            $options['options.' . $option->prop_key] = $option->prop_value;
        }

        return array_merge([
            'users' => User::with('roles')
                ->whereJsonLength('permissions', '=', 0)
                ->orWhere('permissions', '=', null)
                ->whereDoesntHave('roles')
                ->filters()
                ->filtersApplySelection(UserFiltersLayout::class)
                ->defaultSort('id', 'desc')
                ->paginate(),
        ], $options);
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
                ->href(route('platform.systems.users.create')),
            Button::make('Download users')
                ->rawClick()
                ->method('downloadUsers')
                ->novalidate()
                ->icon('icon-arrow-down-circle'),
            ModalToggle::make(__('Opciones'))
                ->icon('icon-options')
                ->method('updateOptions')
                ->modal('optionsModal'),
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
            UserListLayout::class,

            Layout::modal('optionsModal', [
                Layout::rows([
                    Switcher::make('options.automatically_activate_users')
                        ->title('Activar usuarios automáticamente')
                        ->sendTrueOrFalse(),
                ])
            ])->title('Opciones'),
            Layout::modal('oneAsyncModal', [
                UserEditLayout::class,
            ])->async('asyncGetUser'),
        ];
    }

    /**
     * @throws CannotInsertRecord
     */

    public function downloadUsers()
    {
        $csvExporter = new \Laracsv\Export();
        $users = User::get();
        $csvExporter->beforeEach(function ($user) {
            if ($user->country_id)
                $user->country = Country::find($user->country_id)->first()->name;
        });

        $csvExporter->build($users,
            [
                'email', 'name', 'last_name',
                'country', 'active', 'confirmed_at'

            ])->download();
    }

    /**
     * @param User $user
     *
     * @return array
     */
    public function asyncGetUser(User $user): array
    {
        return [
            'user' => $user,
        ];
    }

    /**
     * @param User $user
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function saveUser(User $user, Request $request)
    {
        $request->validate([
            'user.email' => 'required|unique:users,email,' . $user->id,
        ]);

        $user->fill($request->get('user'))
            ->replaceRoles($request->input('user.roles'))
            ->save();

        Toast::info(__('User was saved.'));

        return back();
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function remove(Request $request)
    {
        User::findOrFail($request->get('id'))
            ->delete();
        Toast::info(__('User was removed'));

        return back();
    }

    /**
     * @param User $user
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateOptions(Request $request)
    {

        foreach ($request->get('options') as $key => $value){
            Options::updateOrCreate(
                ['prop_key' => $key],
                [
                    'prop_key' => $key,
                    'prop_value' => $value,
                ]
            );
        }
        Toast::info(__('Opciones actualizadas correctamente.'));

        return back();
    }
}
