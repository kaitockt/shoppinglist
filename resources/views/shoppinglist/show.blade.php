@extends('layouts.app')

@section('JSLibraries')
<!--List of Favourites-->
<script src="{{ asset('js/autocomplete.js') }}"></script>
<script>
    const fav = [
        @foreach ( Auth::user()->favourites()->get() as $fav)
            "{{ $fav->name }}"
        @endforeach
    ];
    window.addEventListener('load', function(e){
        autocomplete(document.getElementById("quickAddName"), fav);
    })
</script>

<link rel="stylesheet" href="{{ asset('css/autocomplete.css') }}">
@endsection

@section('content')
<div class="m-auto py-10">
    <div class="text-center">
        <h1 class="text-5xl uppercase bold">
            {{ $list->name }}
        </h1>
    </div>
    <div class="flex justify-between mt-10">
        <p>Creator: {{ $list->creator->name }}</p>
        <p>
            @if(count($list->users->where(['status', 1])) > 1)
            Shared by: {{ $list->users->where(['id', '<>', $list->creator->id])->implode('name', ', ')}}
            @endif
        </p>
    </div>

    <table class="min-w-full table-fixed my-5">
        <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="w-1/6 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Priority</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Buy By</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody class="bg-white">
        @forelse ($list->items->where('done', 0)->sortBy('priority') as $item)
            <tr x-data="{otherOpen_{{ $item->id }}: false}">
                <td class="px-6 py-4">{{ floatval($item->priority) /* remove .00*/}}</td>
                <td class="px-6 py-4">
                    {{ $item->name }}
                    @if ($item->repeat)
                        <i class="fas fa-retweet text-gray-600"></i>
                        {{-- TODO: Tooltip to show frequency of repeating --}}
                    @endif
                </td>
                <td class="px-6 py-4">
                    {{ str_replace(" 00:00:00", "", $item->buy_by) }}
                    {{-- TODO: Show date only, hide time --}}
                </td>
                <td>
                    <div>
                        <form action="/listitems/{{ $item->id }}/done" method="post" class="inline">
                        @csrf
                        <button class="text-white bg-green-500 p-2 w-30 uppercase mx-1 text-xs">
                            <i class="fas fa-check text-white px-1"></i>
                            <span class="font-semibold">Done</span>
                        </button>
                        </form>
                        <a href="#" @click="otherOpen_{{ $item->id }} = !otherOpen_{{ $item->id }}">
                            <i class="fas fa-ellipsis-h text-gray-500 px-1"></i>
                        </a>
                    </div>
                    <div class="py-3"
                        :class="{'block':otherOpen_{{ $item->id }}, 'hidden':!otherOpen_{{ $item->id }}}"
                        @click.away="otherOpen_{{ $item->id }} = false">
                        @if($item->fav())
                            {{-- favourite --}}
                            <a href="/favourites/remove/{{ $item->name }}" class="has-tooltip">
                                <i class="fas fa-heart text-red-500 px-1"></i>
                                <span class="tooltip tooltip-bottom p-2 rounded bg-gray-800 text-white">
                                    Remove From Favourite
                                </span>
                            </a>
                        @else
                            {{-- not favourite --}}
                            <a href="/favourites/add/{{ $item->name }}" class="has-tooltip">
                                <i class="far fa-heart text-gray-500 px-1"></i>
                                <span class="tooltip tooltip-bottom p-2 rounded bg-gray-800 text-white">
                                    Add To Favourite
                                </span>
                            </a>
                        @endif
                        {{-- edit --}}
                        <a href="/listitems/{{ $item->id }}/edit" class="has-tooltip">
                            <i class="fas fa-edit text-gray-500 px-1 hover:text-gray-900"></i>
                            <span class="tooltip tooltip-bottom p-2 rounded bg-gray-800 text-white">
                                Edit
                            </span>
                        </a>
                        {{-- remove --}}
                        {{-- TODO: Add Confirmation Alert --}}
                        <a href="{{ route('listitems.destroy', ['listitem' => $item->id]) }}" class="has-tooltip"
                            onclick="event.preventDefault();
                                document.getElementById('remove-item-form-{{ $item->id }}').submit();">
                            <i class="fas fa-trash-alt text-gray-500 px-1 hover:text-gray-900"></i>
                            <span class="tooltip tooltip-bottom p-2 rounded bg-gray-800 text-white">
                                Remove
                            </span>
                        </a>
                        <form action="{{ route('listitems.destroy', ['listitem' => $item->id]) }}" id="remove-item-form-{{ $item->id }}" class="hidden" method="post">
                            {{ csrf_field() }}
                            @method('DELETE')
                        </form>
                    </div>
                </td>
            </tr>
            @empty
                <tr>
                    <td colspan="3">No items yet</td>
                </tr>
            @endforelse
            <form action="{{ route('list.quickAdd', ['list' => $list->id]) }}"
                autocomplete="off" method="post">
                @csrf
                <tr class="bg-teal-100">
                    <td class="px-6 py-4">Quick Add</td>
                    <td class="px-6 py-2" colspan="2">
                        <div class="autocomplete w-full">
                            <input id="quickAddName" type="text" name="name" class="w-full py-2 px-4">
                        </div>
                        @if ($errors->any())
                                @foreach ($errors->all() as $error)
                                    <li class="text-red-500 list-none">
                                        {{ $error }}
                                    </li>
                                @endforeach
                        @endif
                    </td>
                    <!--Buy by function not applicable for quick add-->
                    <td>
                        <button
                            class="text-white bg-green-500 p-2 w-30 uppercase mx-1 text-sm whitespace-nowrap">
                            <i class="fas fa-plus text-white px-1"></i>
                            <span class="font-semibold">Add</span>
                        </button>
                    </td>
                </tr>
            </form>
        </tbody>
    </table>
    <button
        class="text-white bg-green-500 p-2 w-full uppercase text-lg font-semibold rounded"
        onclick="location.href='{{ route('list.detailedAdd', ['list' => $list->id]) }}'"
        >Add Item</button>
</div>
@endsection

{{-- TODO: Drag items to rearrange --}}