<?php

declare(strict_types=1);

use App\Orchid\Screens\AdminUser\AdminCreateScreen;
use App\Orchid\Screens\AdminUser\AdminEditScreen;
use App\Orchid\Screens\AdminUser\AdminListScreen;
use App\Orchid\Screens\Building\BuildingCreateScreen;
use App\Orchid\Screens\Building\BuildingEditScreen;
use App\Orchid\Screens\Building\BuildingListScreen;
use App\Orchid\Screens\Costs\CostListScreen;
use App\Orchid\Screens\Costs\DesignCostListScreen;
use App\Orchid\Screens\Examples\ExampleCardsScreen;
use App\Orchid\Screens\Examples\ExampleChartsScreen;
use App\Orchid\Screens\Examples\ExampleFieldsAdvancedScreen;
use App\Orchid\Screens\Examples\ExampleFieldsScreen;
use App\Orchid\Screens\Examples\ExampleLayoutsScreen;
use App\Orchid\Screens\Examples\ExampleScreen;
use App\Orchid\Screens\Examples\ExampleTextEditorsScreen;
use App\Orchid\Screens\Examples\TestScreen;
use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\Space\SpaceCreateScreen;
use App\Orchid\Screens\Space\SpaceEditScreen;
use App\Orchid\Screens\Space\SpaceListScreen;
use App\Orchid\Screens\User\UserCreateScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\Zone\ZoneCreateScreen;
use App\Orchid\Screens\Zone\ZoneEditScreen;
use App\Orchid\Screens\Zone\ZoneListScreen;
use App\Orchid\Screens\Parameter\ParameterListScreen;
use App\Orchid\Screens\Parameter\ParameterEditScreen;
use App\Orchid\Screens\Costs\DescriptionCostListScreen;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the need "dashboard" middleware group. Now create something great!
|
*/

// Main
Route::screen('/main', PlatformScreen::class)->name('platform.main');

// Users...
Route::screen('users/{users}/edit', UserEditScreen::class)->name('platform.systems.users.edit');
Route::screen('users/create', UserCreateScreen::class)->name('platform.systems.users.create');
Route::screen('users', UserListScreen::class)->name('platform.systems.users');

// Admin Users...
Route::screen('admin/{users}/edit', AdminEditScreen::class)->name('platform.systems.admin.edit');
Route::screen('admin/create', AdminCreateScreen::class)->name('platform.systems.admin.create');
Route::screen('admin', AdminListScreen::class)->name('platform.systems.admin');

// Roles...
Route::screen('roles/{roles}/edit', RoleEditScreen::class)->name('platform.systems.roles.edit');
Route::screen('roles/create', RoleEditScreen::class)->name('platform.systems.roles.create');
Route::screen('roles', RoleListScreen::class)->name('platform.systems.roles');


//Space...
Route::screen('spaces', SpaceListScreen::class)->name('platform.modules.spaces');
Route::screen('space/create', SpaceCreateScreen::class)->name('platform.modules.spaces.create');
Route::screen('space/{spaces}/edit', SpaceEditScreen::class)->name('platform.modules.spaces.edit');

//Zone...
Route::screen('zones', ZoneListScreen::class)->name('platform.modules.zones');
Route::screen('zone/create', ZoneCreateScreen::class)->name('platform.modules.zones.create');
Route::screen('zone/{zones}/edit', ZoneEditScreen::class)->name('platform.modules.zones.edit');

//Buildings...
Route::screen('buildings', BuildingListScreen::class)->name('platform.modules.buildings');
Route::screen('building/create', BuildingCreateScreen::class)->name('platform.modules.buildings.create');
Route::screen('building/{zones}/edit', BuildingEditScreen::class)->name('platform.modules.buildings.edit');

//Designs costs...
Route::screen('designs', DesignCostListScreen::class)->name('platform.modules.costs.designs');

//Costs...
Route::screen('costs', CostListScreen::class)->name('platform.modules.costs');

//Description costs...
Route::screen('descriptions', DescriptionCostListScreen::class)->name('platform.modules.costs.descriptions');

//Global Parameters...
Route::screen('parameters', ParameterListScreen::class)->name('platform.systems.parameters');
Route::screen('parameter/{parameters}/edit', ParameterEditScreen::class)->name('platform.systems.parameters.edit');

// Example...
Route::screen('example', ExampleScreen::class)->name('platform.example');
Route::screen('example-fields', ExampleFieldsScreen::class)->name('platform.example.fields');
Route::screen('example-layouts', ExampleLayoutsScreen::class)->name('platform.example.layouts');
Route::screen('example-charts', ExampleChartsScreen::class)->name('platform.example.charts');
Route::screen('example-editors', ExampleTextEditorsScreen::class)->name('platform.example.editors');
Route::screen('example-cards', ExampleCardsScreen::class)->name('platform.example.cards');
Route::screen('example-advanced', ExampleFieldsAdvancedScreen::class)->name('platform.example.advanced');

//Example
Route::screen('test', TestScreen::class)->name('platform.test');

//Route::screen('/dashboard/screen/idea', 'Idea::class','platform.screens.idea');
