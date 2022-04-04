<?php

declare(strict_types=1);

namespace App\Orchid\Screens\AdminUser;

use App\Models\Country;
use App\Orchid\Layouts\AdminUser\AdminEditLayout;
use App\Orchid\Layouts\AdminUser\AdminFiltersLayout;
use App\Orchid\Layouts\AdminUser\AdminListLayout;
use App\Orchid\Layouts\User\UserEditLayout;
use App\Orchid\Layouts\User\UserFiltersLayout;
use App\Orchid\Layouts\User\UserListLayout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layout;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class AdminListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Usuarios Administradores';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Usuarios administradores creados';

    /**
     * @var string
     */
    public $permission = 'platform.systems.admin';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'users' => User::with('roles')
                ->where('permissions', '!=', null)
                ->whereJsonLength('permissions','!=', 0)
                ->orWhereHas('roles')
                ->filters()
                ->filtersApplySelection(UserFiltersLayout::class)
                ->defaultSort('id', 'desc')
                ->paginate(),
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
                ->href(route('platform.systems.admin.create')),
            Button::make('Download users')->rawClick()
                ->method('downloadUsers')
                ->novalidate()
                ->icon('icon-arrow-down-circle'),
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
            AdminFiltersLayout::class,
            AdminListLayout::class,

            Layout::modal('oneAsyncModal', [
                AdminEditLayout::class,
            ])->async('asyncGetUser'),
        ];
    }

    public function downloadUsers()
    {
        $csvExporter = new \Laracsv\Export();
        $users = User::get();
        $csvExporter->beforeEach(function ($user) {
            $user->country = Country::find($user->country_id)->name;
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
}
