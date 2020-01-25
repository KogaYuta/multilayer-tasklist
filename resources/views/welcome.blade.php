@extends('layouts.app')

@section('content')
    @if (Auth::check())
        <p>{{ Auth::user()->name }}さんのタスクリスト</p>
        {{-- projectと選択されたtaskの一覧を表示 --}}
        @foreach ($projects as $project)
            <ul style="color:#333;">
                <li>
                    {!! link_to_route('tasks.tree', $project->content, ['id'=>$project->id]) !!}
                    
                    <ul>
                        @foreach($tasksObject[$project->id] as $task)
                            <li>
                                {{ $task->content }}
                            </li>
                        @endforeach
                    </ul>
                </li>
            </ul>
        @endforeach 
        
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