@extends('layouts.app')

@section('JSLibraries')
<!--JS file for this page-->
<script src="{{ asset('js/additem.js') }}"></script>
<!--List of Favourites-->
<script src="{{ asset('js/autocomplete.js') }}"></script>
<script>
    const fav = [
        @foreach ( Auth::user()->favourites()->get() as $fav)
            "{{ $fav->name }}"
        @endforeach
    ];
    window.addEventListener('load', function(e){
        autocomplete(document.getElementById("name"), fav);
    })
</script>

<link rel="stylesheet" href="{{ asset('css/autocomplete.css') }}">
@endsection

@section('content')
<a href="/list/@yield("list_id")" class="flex pt-5 text-gray-400 hover:text-black">&larr; Back to Shopping List</a>
<div class="m-auto py-19">
    <h1 class="text-5xl bold text-center py-5">
        @yield('title')
    </h1>
    @if ($errors->any())
        <div class="w-1/2 m-auto text-center text-red-500">
            <ul>
                @foreach ($errors->all() as $error)
                    <li class="list-none">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('formTag')
        @csrf
        <div class="flex justify-center pt-20">
            <div class="block">
                <div class="autocomplete">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Name</label>
                    <input id="name" type="text" name="name"
                        class="block w-80 py-2 px-4 my-2"
                        {{ isset($item)?"value=$item->name":"" }}
                        > 
                </div>
                
                
                <label class="block text-gray-700 text-sm font-bold mb-2" for="priority">Priority</label>
                <input type="number" id="priority" name="priority" step="1"
                    class="block w-80 py-2 px-4 my-2" value="@yield('priorityVal')"
                    >    
                <!--TODO: Add referene to existing priorities-->
                
                <label class="block text-gray-700 text-sm font-bold mb-2" for="buy-by">Buy By(Optional)</label>
                <input type="date" name="buy-by" id="buy-by" class="block w-80 py-2 px-4 my-2"
                    {{ isset($item->buy_by)? "value=$item->buy_by":"" }}>
                
                <div class="w-80 flex justify-between mt-5">
                    <input type="checkbox" name="repeat" id="cb-repeat" class="form-checkbox my-2"
                        {{ isset($item) && $item->repeat != ""?"checked":""}}>
                    <label for="cb-repeat" class="py-2">Repeat Every</label>
                    <div class="inline flex w-3/5">
                        <input type="number" name="repeat-number" id="repeat-number"
                        class="py-2 px-4 ml-2 w-1/2 flex" @yield("repeatNumber")>
                        <select name="repeat-unit" id="repeat-unit" class="py-2 px-2 flex"
                        @if(!isset($item)||!$item->repeat)
                            disabled
                        @endif
                        >
                            @foreach(["day", "week", "month", "year"] as $unit)
                                <option value="{{ $unit }}"
                                @if (isset($item) && str_contains($item->repeat, " ") && explode(" ", $item->repeat)[1] == $unit)
                                    selected
                                @endif>
                                    {{ ucfirst($unit) }}(s)
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <button
                    class="bg-green-600 block shadow-5xl my-10 p-2 w-80 uppercase font-semibold text-white"
                    type="submit">
                    SUBMIT
                </button>
            </div>
        </div>
    </form>
</div>
@endsection