@extends('layouts.app')

@section('content')

    <h1>id: {{ $project->id }} のプロジェクトの編集ページ</h1>

    <div class="row">
        <div class="col-6">
            {!! Form::model($project, ['route' => ['projects.update', $project->id], 'method' => 'put']) !!}
        
                <div class="form-group">
                    {!! Form::label('content', 'プロジェクト名:') !!}
                    {!! Form::text('content', null, ['class' => 'form-control']) !!}
                </div>
        
                {!! Form::submit('更新', ['class' => 'btn btn-primary']) !!}
        
            {!! Form::close() !!}
        </div>
    </div>

@endsection