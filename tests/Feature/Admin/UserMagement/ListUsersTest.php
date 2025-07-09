<?php

use App\Models\User;

use function Pest\Laravel\{actingAs, get};

test('it should be able to access the route admin/users', function () {

    actingAs(
        User::factory()->admin()->create()
    );

    get(route('admin.users'))
        ->assertOk();

});

test('making sure that the route is protecd by the permission BE_AN_ADMIN', function () {
    actingAs(
        User::factory()->create()
    );

    get(route('admin.users'))
        ->assertForbidden();
});
