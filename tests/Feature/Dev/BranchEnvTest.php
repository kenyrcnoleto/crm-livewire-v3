<?php

// use Symfony\Component\Process\Process;

use App\Livewire\Dev\BranchEnv;
use App\Models\User;
use Illuminate\Support\Facades\Process;
use Livewire\Livewire;

use function Pest\Laravel\{actingAs, get};

test('it should show a current branch in the page', function () {

    // dd(shell_exec('git branch --show-current'));

    Process::fake([
        'git branch --show-current' => Process::result(output: 'feature'),
    ]);

    // $process = Process::run('git branch --show-current');

    Livewire::test(BranchEnv::class)
        ->assertSet('branch', 'feature')
        ->assertSee('feature');

    Process::assertRan('git branch --show-current');

    // dd($process->output());

});

test('it should not load the livewire component on production environment', function () {
    $user = User::factory()->create();

    app()->detectEnvironment(fn () => 'production');

    actingAs($user);

    get(route('dashboard'))  //app.blade.php
        ->assertDontSeeLivewire('dev.branch-env');

    get(route('login'))  //guest.blade.php
    ->assertDontSeeLivewire('dev.branch-env');
});

test('it should load de livewire componente on nom production environment', function () {
    $user = User::factory()->create();

    app()->detectEnvironment(fn () => 'local');

    actingAs($user);

    get(route('dashboard'))  //app.blade.php
        ->assertSeeLivewire('dev.branch-env');

    get(route('login'))  //guest.blade.php
    ->assertSeeLivewire('dev.branch-env');
});
