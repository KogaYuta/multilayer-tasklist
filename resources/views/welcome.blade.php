@extends('layouts.app')

@section('content')
    @if (Auth::check())
        <p>{{ Auth::user()->name }}さんのやることリスト</p>
        {{-- projectと選択されたtaskの一覧を表示 --}}
        <div style='display:flex'> {{--style='display:flex'--}}
            @foreach ($projects as $project)
                <div class="mr-4">
                    {{--新しい表示--}}
                    {!! link_to_route('tasks.tree', $project->content, ['id'=>$project->id]) !!}
                    <div>
                        達成率：{{ $tasksCompleted[$project->id] }}%
                    </div>
                    <table class="table table-bordered">
                      <thead class="thead-dark">
                        <tr>
                          <th scope="col" style="width:20px">check</th>
                          <th scope="col">タスク名</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($tasksObject[$project->id] as $task)
                            <tr>
                                <th scope="col">
                                    @if ($task->selected)
                                        {{ Form::checkbox($project->id, $task->id, true, ['class'=>'check']) }}
                                    @else
                                        {{ Form::checkbox($project->id, $task->id, false, ['class'=>'check']) }}
                                    @endif
                                </th>
                                <th scope="col">
                                    {{ $task->content }}
                                </th>
                            </tr>
                        @endforeach
                      </tbody>
                    </table>
                    {{--新しい表示--}}
                </div>
            @endforeach 
        </div>
        
    @else
        <div class="center jumbotron">
            <div class="text-center">
                <h1>Welcome to the Multilayer Tasklist</h1>
                {!! link_to_route('login', 'Login now!', [], ['class' => 'btn btn-lg btn-danger']) !!}
                {!! link_to_route('signup.get', 'Sign up now!', [], ['class' => 'btn btn-lg btn-primary']) !!}
            </div>
        </div>
    @endif
@endsection