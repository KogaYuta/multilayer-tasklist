@extends('layouts.app')

@section('content')

    <h1>プロジェクト新規作成ページ</h1>

    <div class="row">
        <div class="col-6">
            {!! Form::model($project, ['route' => 'projects.store']) !!}
        
                <div class="form-group">
                    {!! Form::label('content', 'プロジェクト名:') !!}
                    {!! Form::text('content', null, ['class' => 'form-control']) !!}
                </div>
        
                {!! Form::submit('作成', ['class' => 'btn btn-primary']) !!}
        
            {!! Form::close() !!}
        </div>
    </div>
@endsection