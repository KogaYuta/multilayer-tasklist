@extends('layouts.app')

@section('content')
    @if (Auth::check())
        <h1>{{ Auth::user()->name }}さん、今日も頑張りましょう!</h1>
        
        {!! link_to_route('projects.create', '新規プロジェクトの作成', [], ['class' => 'btn btn-primary mb-2', 'style'=>'width:100%;height:50px;']) !!}
        
        @if (count($projects) > 0)
        
            {{-- projectと選択されたtaskの一覧を表示 --}}
            <table class="table table-bordered">
              <thead class="thead-dark">
                <tr>
                  <th scope="col">プロジェクト名</th>
                  <th scope="col">編集</th>
                  <th scope="col">削除</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($projects as $project)
                    <tr>
                        <td>
                            {!! link_to_route('tasks.tree', $project->content, ['id'=>$project->id],['class'=>'mr-4']) !!}
                        </td>
                        <td>
                            {!! link_to_route('projects.edit', '編集', ['id'=>$project->id], ['class' => 'btn btn-primary'],['class'=>'mr-4']) !!}
                        </td>
                        <td>
                            {!! Form::model($project, ['route' => ['projects.destroy', $project->id], 'method' => 'delete']) !!}
                                {!! Form::submit('削除', ['class' => 'btn btn-danger']) !!}
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @endforeach
              </tbody>
            </table>
            
        
        @else
        
            {{--projectがない場合は、projectを作るように促す--}}
            <p>新しいプロジェクトを作りましょう</p>
        
        @endif
        
    @endif
@endsection