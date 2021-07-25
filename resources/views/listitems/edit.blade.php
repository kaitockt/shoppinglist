@extends('listitems\itemform')

@section('list_id'){{ $item->list->id }}@endsection

@section('title')
Edit Item in {{ $item->list->name }}
@endsection

@section('formTag')
    <form action="{{ route('listitems.update', ['listitem' => $item->id]) }}" method="post" autocomplete="off">
        {{ method_field('PUT') }}
@endsection

@section('priorityVal'){{ floatval($item->priority) }}@endsection

@section('repeatNumber')
    {{ ($item->repeat?"value=".explode(" ", $item->repeat)[0]:"") }}
@endsection