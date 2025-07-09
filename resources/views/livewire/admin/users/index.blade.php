<div>
    {{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}
    @foreach ($this->users as $user)
        <li>{{ $user->name }}</li>
    @endforeach
</div>
