<?php

use App\Models\{Can, Permission, User};
use Database\Seeders\{PermissionSeeder, UsersSeeder};
use Illuminate\Support\Facades\{Cache, DB};

use function Pest\Laravel\{actingAs, assertDatabaseHas, seed};

test('it should be able to give an user a permission to do something', function () {

    /** @var User $user */
    $user = User::factory()->create();

    $user->givePermissionTo(Can::BE_AN_ADMIN);

    expect($user)
        ->hasPermissionTo(Can::BE_AN_ADMIN)
        ->toBeTrue();

    assertDatabaseHas('permissions', [
        'key' => Can::BE_AN_ADMIN->value,
    ]);

    assertDatabaseHas('permission_user', [
        'user_id'       => $user->id,
        'permission_id' => Permission::where('key', '=', Can::BE_AN_ADMIN->value)->first()->id,
    ]);

});

test('permission has to have a seeder', function () {
    $this->seed(PermissionSeeder::class);

    assertDatabaseHas('permissions', [
        'key' => Can::BE_AN_ADMIN->value,
    ]);
});

test('seed with an admin user', function () {

    seed([PermissionSeeder::class, UsersSeeder::class]);

    assertDatabaseHas('permissions', [
        'key' => Can::BE_AN_ADMIN->value,
    ]);

    assertDatabaseHas('permission_user', [
        'user_id'       => User::first()?->id,
        'permission_id' => Permission::where('key', '=', Can::BE_AN_ADMIN->value)->first()?->id,
    ]);
});

test('it should block the access to an admin page if the user does not have the permission to be an admin', function () {

    $user = User::factory()->create();

    actingAs($user)
        ->get(route('admin.dashboard'))
        ->assertForbidden();

});

test("Let's make sure that we are using cache to store user permissions", function () {
    $user = User::factory()->create();

    $user->givePermissionTo(Can::BE_AN_ADMIN);
    // dd($user->permissions());

    $cacheKey = "user::{$user->id}::permissions";

    expect(Cache::has($cacheKey))->toBeTrue('Checking if cache key exists')
        ->and(Cache::get($cacheKey))->toBe($user->permissions, 'Checking if permissions are the same as the user');

});

test("let's make sure that we are using the cache the retrieve/check when the user has the given permission", function () {
    $user = User::factory()->create();

    $user->givePermissionTo(Can::BE_AN_ADMIN);
    // dd($user->permissions());

    //Verificar que eu não tive nenhum hit no banco de dados a partir deste ponto
    DB::listen(fn ($query) => throw new Exception('We got a hit'));

    $user->hasPermissionTo(Can::BE_AN_ADMIN);

    expect(true)->tobeTrue();
});
