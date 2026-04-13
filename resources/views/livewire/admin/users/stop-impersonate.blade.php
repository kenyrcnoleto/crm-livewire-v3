<div class=" bg-yellow-100 text-yellow-900 px-4 p-1 rounded mb-4">
            You're impersonating <span class="font-bold" >{{ $user->name }}</span>,
            <button class="hover:underline hover:text-yellow-300 font-bold" wire:click="stopImpersonate()" >click here
        </button>
        to stop impersonating.
</div>
