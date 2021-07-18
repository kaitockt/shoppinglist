@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h1 class="text-5xl py-5">Shopping Lists</h1>
    @forelse ($shoppingLists as $shoppingList)
        <p>{{ $shoppingList->name }}</p>
    @empty
        
    @endforelse
    <h2 class="text-4xl py-5">Invitations</h2>
    @forelse ($invitations as $invitation)
        <p>{{ $invitation->name }}</p>
    @empty
        <p>No invitations for the moment.</p>
    @endforelse
</div>
@endsection