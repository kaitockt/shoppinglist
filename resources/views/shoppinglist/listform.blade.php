@extends('layouts.app')

@section('JSLibraries')
    <!--Tagify-->
    <script src="https://unpkg.com/@yaireo/tagify"></script>
    <script src="https://unpkg.com/@yaireo/tagify/dist/tagify.polyfills.min.js"></script>
    <link href="https://unpkg.com/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />

    <!--JS for create shoppinglist-->

    {{-- Prepare existing tags for tagify --}}
    @if (isset($list))
        <script>
            let existingTags = [
                @forelse ($list->users->where('id', '<>', $list->created_by) as $user)   //list all shared users except the creator
                    {id: {{ $user->id }}, value: "{{ $user->name }}"},
                @empty
                    
                @endforelse
            ]
        </script>
    @endif
    <script src="{{ asset('js/listForm.js') }}"></script>
    @if(Auth::check())
        <script>
            const uid = {{ Auth::user()->id }};
        </script>
    @endif
    

@endsection

@section('content')
<div class="m-auto py-24">
    <div class="text-center">
        <h1 class="text-5xl uppercase bold">
            @yield('title')
        </h1>
    </div>
    @if ($errors->any())
        <div class="m-auto text-center">
            @foreach ($errors->all() as $error)
                <li class="text-red-500 list-none">
                    {{ $error }}
                </li>
            @endforeach
        </div>
    @endif
    <div class="flex justify-center pt-20">
        <div class="block">
            @if (isset($list))   {{-- Edit list --}}
                <form action="/list/{{ $list->id }}" method="POST">
                    {{ method_field('PUT') }}
            @else               {{-- Create list --}}
                <form action="/list" method="POST">
            @endif
                @csrf
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                    Name
                </label>
                <input
                    id="name"
                    type="text"
                    name="name"
                    placeholder="Name for the New Shopping List"
                    value="{{ isset($list)?$list->name:Auth::user()->name."'s Shopping List" }}"
                    class="block shadow-5xl mb-10 p-2 w-80 italic placeholder-gray-400">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="invite">
                    Share with your family and friends (optional)
                </label>
                <input
                    id="invite"
                    type="text"
                    name="invite"
                    placeholder="Input the names of your friends"
                    class="block shadow-5xl mb-10 p-2 w-80 italic placeholder-gray-400">
                <button
                    class="bg-green-600 block shadow-5xl mb-10 p-2 w-80 uppercase font-semibold text-white"
                    type="submit">
                    SUBMIT
                </button>
                
            </form>
            @if (isset($list))
                <form action="/list/{{ $list->id}}" method="post">
                    @csrf
                    @method('DELETE')
                    <button
                        class="bg-red-600 block shadow-5xl mb-10 p-2 w-80 uppercase font-semibold text-white"
                        type="submit">
                        delete this list
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection