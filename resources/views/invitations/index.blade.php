@extends('layouts/app')

@section('content')
<div class="container mx-auto">
    <h1 class="text-5xl py-5 text-center">Invitations</h1>
    <table class="min-w-full table-auto my-5">
        <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Inviter</th>
                <th></th>
            </tr>
        </thead>
        <tbody class="bg-white">
            @forelse ($invitations as $invitation)
            <tr>
                <td class="px-6 py-4">
                    {{ $invitation->shoppingList->name }}
                </td>
                <td class="px-6 py-4">
                    {{ $invitation->inviter->name }}
                </td>
                <td class="px-6 py-4 flex float-right">
                    {{-- Buttons --}}

                    {{-- Accept --}}
                    <form action="{{
                        route('invitations.accept', [
                            'list' => $invitation['shoppinglist']['id'],
                            'uid' => Auth::id(),
                            ]) }}" method="post">
                        @csrf
                        {{ method_field('PUT') }}
                        <button class="text-white bg-green-500 hover:bg-green-600 p-2 w-30 uppercase mx-1 text-xs">
                            Accept
                        </button>
                    </form>
                    {{-- decline --}}
                    <form action="{{
                        route('invitations.decline', [
                            'list' => $invitation['shoppinglist']['id'],
                            'uid' => Auth::id(),
                            ]) }}" method="post">
                        @csrf
                        {{ method_field('DELETE') }}
                        <button class="text-white bg-red-500 hover:bg-red-600 p-2 w-30 uppercase mx-1 text-xs">
                            Decline
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="px-6 py-4">
                    There is no invitations at the moment. 
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection