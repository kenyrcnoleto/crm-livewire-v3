<div>
    {{-- The Master doesn't talk, he acts. --}}
    @if ($user)
    {{ $user->name }}
    {{ $user->email }}
    {{ $user->created_at->format('d/m/Y H:i') }}
    {{ $user->updated_at->format('d/m/Y H:i') }}
    {{ $user->deleted_at?->format('d/m/Y H:i') ?? 'N/A' }}
    {{ $user->createdBy?->name ?? 'N/A' }}
    {{ $user->deletedBy->name ?? 'N/A' }}
    @endif
</div>
