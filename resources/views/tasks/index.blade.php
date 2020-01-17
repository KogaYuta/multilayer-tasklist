@extends('layouts.app')

@section('content')

<h1>タスクツリー</h1>

@if (count($tasks) > 0)
    <span id="js-getTasks" data-task="{{ $tasks }}"></span>
    <svg width="8000" height="6000">
    </svg>
    
@endif

@endsection