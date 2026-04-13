<div class=" bg-yellow-100 text-yellow-900 px-4 p-1 rounded mb-4" wire:click="stopImpersonate()"> >
            You're impersonating <span class="font-bold" >{{ $user->name }}</span>,
            {{ __("You're impersonating :name, click here to stop impersonating.", ['name' => $user->name]) }}
</div>
