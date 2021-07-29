@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h1 class="text-5xl py-5 text-center">My Shopping Lists</h1>
    <ul>
        @forelse ($shoppingLists as $list)
            <li
            class="rounded px-4 py-4 border-2 my-2 text-lg hover:bg-gray-200 flex justify-between">
                <a href="\list\{{ $list->id }}">{{ $list->name }}
                    <span class="italic text-sm text-gray-500">({{ $list->validItemsCount }} items)</span>
                </a>
                <div>
                    <!--Buttons-->
                    {{-- Edit --}}
                    <a href="\list\{{ $list->id }}\edit" class="has-tooltip">
                        <i class="fas fa-edit text-gray-500 px-1 hover:text-gray-900"></i>
                        <span class="tooltip tooltip-bottom p-2 rounded bg-gray-800 text-white">
                            Edit
                        </span>
                    </a>
                    {{-- delete --}}
                    <a href="#" class="has-tooltip">
                        <i class="fas fa-trash-alt text-gray-500 px-1 hover:text-gray-900"></i>
                        <span class="tooltip tooltip-bottom p-2 rounded bg-gray-800 text-white">
                            Delete
                        </span>
                    </a>
                </div>
            </li>
        @empty
        <li class="rounded px-2 py-2 border-2 my-2">
            There is no shopping list at the moment. 
            <a href="{{ route("list.create") }}">Create One</a>
        </li>
        @endforelse
    </ul>
    <button
        class="w-full rounded px-2 py-4 my-2 bg-green-500 text-white hover:bg-green-600 text-xl uppercase"
        onclick="location.href='{{ route('list.create') }}'">
        create new
    </button>
</div>
@endsection