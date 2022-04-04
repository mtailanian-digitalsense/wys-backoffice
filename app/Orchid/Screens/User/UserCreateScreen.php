<?php

declare(strict_types=1);

namespace App\Orchid\Screens\User;

use App\Models\Options;
use App\Models\UserVerification;
use App\Notifications\NewUserPassword;
use App\Orchid\Layouts\User\UserCreateLayout;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Layout;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;

class UserCreateScreen extends Screen
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
    public $description = 'Details such as name, email and password';

    /**
     * @var string
     */
    public $permission = 'platform.systems.users';

    /**
     * @var bool
     */
    private $isAutoActive = true;

    /**
     * Query data.
     *
     * @param User $user
     *
     * @return array
     */
    public function query(User $user): array
    {
        $user->load(['roles']);

        $is_auto_active = Options::firstWhere('prop_key', '=', 'automatically_activate_users');
        if (isset($is_auto_active)) {
            if ($is_auto_active->prop_value) {
                $this->isAutoActive = false;
            }
        }


        return [
            'user' => $user,
            'permission' => $user->getStatusPermission(),
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
     * @return Layout[]
     * @throws \Throwable
     *
     */
    public function layout(): array
    {
        return [
            UserCreateLayout::class,
            Layout::rows([
                CheckBox::make('user.active')
                    ->title('Estado usuario')
                    ->sendTrueOrFalse()
                    ->canSee($this->isAutoActive)
                    ->placeholder('Usuario activo'),
            ]),
            Layout::modal('password', [
                Layout::rows([
                ]),
            ]),
        ];
    }

    /**
     * @param User $user
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(User $user, Request $request)
    {

        // Validation
        $validator = Validator::make($request->all(), [
            'user.email' => 'required|unique:users,email,' . $user->id,
            'user.password' => 'required|confirmed|min:8',
        ])->setAttributeNames(
            [
                'user.email' => 'Correo',
                'user.password' => 'Contraseña',
                'user.password_confirmation' => 'Confirmación contraseña',
            ]
        );


        if ($validator->fails()) {
            if ($validator->errors()->get('user.email')) {
                $user = User:: withTrashed()->where('email', '=', $request->input('user.email'))->first();
                $user->restore();
                Alert::warning('Usuario existía previamente pero fue eliminado. Ha sido restaurado.');
                return redirect()->route('platform.systems.users.edit', $user->id);
            }
            return back()->withErrors($validator);
        }

        $userInfo = $request->get('user');
        if(!isset($userInfo['active']))
            $userInfo['active'] = 1;

        $user->fill($userInfo)->save();
        $user->password = Hash::make($request->input('user.password'));
        $user->confirmed_at = now();
        $user->save();
        $user->replaceRoles($request->input('user.roles'))->save();


        $user->notify(new NewUserPassword($request->input('user.password')));

        Toast::info(__('User was saved.'));

        return redirect()->route('platform.systems.users');
    }
}
