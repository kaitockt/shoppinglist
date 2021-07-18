@extends('layouts.app')

@section('JSLibraries')
    <!--Tagify-->
    <script src="https://unpkg.com/@yaireo/tagify"></script>
    <script src="https://unpkg.com/@yaireo/tagify/dist/tagify.polyfills.min.js"></script>
    <link href="https://unpkg.com/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />
    <!--JS for create shoppinglist-->
    <script src="{{ asset('js/createList.js') }}"></script>
    @if(Auth::check())
        <script>
            const uid = {{ Auth::user()->id }};
        </script>
    @endif
@endsection

@section('content')
<div class="m-auto w-4/8 py-24">
    <div class="text-center">
        <h1 class="text-5xl uppercase bold">
            New Shopping List
        </h1>
    </div>
    @if ($errors->any())
        <div class="w-4/8 m-auto text-center">
            @foreach ($errors->all() as $error)
                <li class="text-red-500 list-none">
                    {{ $error }}
                </li>
            @endforeach
        </div>
    @endif
    <div class="flex justify-center pt-20">
        <form action="/list" method="POST">
            @csrf
            <div class="block">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                    Name
                </label>
                <input
                    id="name"
                    type="text"
                    name="name"
                    placeholder="Name for the New Shopping List"
                    value="{{ Auth::user()->name."'s Shopping List" }}"
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
                    class="bg-green-500 block shadow-5xl mb-10 p-2 w-80 uppercase font-bold"
                    type="submit">
                    SUBMIT
                </button>
            </div>
        </form>

    </div>
</div>
@endsection