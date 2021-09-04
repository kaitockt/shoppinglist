@extends('listitems.itemform')

@section('list_id'){{ $list->id }}@endsection

@section('title')
Add Item to {{ $list->name }}
@endsection

@section('formTag')
<form action="{{ route('list.add', ['list' => $list->id]) }}" method="post" autocomplete="off">
@endsection

{{-- Default Value is set to last of the list --}}
@section('priorityVal'){{ floatval($list->items()->max('priority') + 1) }}@endsection

@section('repeatNumber')
    {{"value=\"1\" disabled" }}
@endsection