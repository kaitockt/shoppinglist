@extends('layouts/app')

@section('content')
<div class="container mx-auto">
    <h1 class="text-5xl py-5 text-center">Invitations</h1>
    <ul>
        @forelse ($invitations as $invitation)
            <li
            class="rounded px-4 py-4 border-2 my-2 text-lg hover:bg-gray-200 flex justify-between">
            {{ $invitation->shoppingList->name }}
            </li>
        @empty
        <li class="rounded px-2 py-2 border-2 my-2">
            There is no invitations at the moment. 
        </li>
        @endforelse
    </ul>
</div>
@endsection