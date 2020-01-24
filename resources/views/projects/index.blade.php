@extends('layouts.app')

@section('content')
    @if (Auth::check())
        <p>{{ Auth::user()->name }}さんのProject</p>
        
        {!! link_to_route('projects.create', '新規プロジェクトの作成', [], ['class' => 'btn btn-primary mb-2']) !!}
        
        {{-- projectと選択されたtaskの一覧を表示 --}}
        @foreach ($projects as $project)
            <div style='display:flex'>
                {!! link_to_route('tasks.tree', $project->content, ['id'=>$project->id],['class'=>'mr-4']) !!}
                {!! link_to_route('projects.edit', '編集', ['id'=>$project->id], ['class' => 'btn btn-primary'],['class'=>'mr-4']) !!}
                {!! Form::model($project, ['route' => ['projects.destroy', $project->id], 'method' => 'delete']) !!}
                    {!! Form::submit('削除', ['class' => 'btn btn-danger']) !!}
                {!! Form::close() !!}
            </div>
        @endforeach 
        
    @else
    
    @endif
@endsection