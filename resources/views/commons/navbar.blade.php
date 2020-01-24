<header class="mb-4">
    <nav class="navbar navbar-expand-sm navbar-dark bg-dark"> 
        <a class="navbar-brand" href="/">多階層ToDoリスト</a>
         
        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#nav-bar">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="nav-bar">
            <ul class="navbar-nav mr-auto"></ul>
            <ul class="navbar-nav">
                @if (Auth::check())
                        {!! link_to_route('logout.get', 'Logout', [], ['style'=>'color:white', 'class'=>'mr-2']) !!}
                        {!! link_to_route('tasks.index', 'Your tasks',[],['style'=>'color:white', 'class'=>'mr-2']) !!}
                        {!! link_to_route('projects.index', 'Project', [],['style'=>'color:white']) !!}
                @else
                    <li class="nav-item">{!! link_to_route('signup.get', 'Signup', [], ['class' => 'nav-link']) !!}</li>
                    <li class="nav-item">{!! link_to_route('login', 'Login', [], ['class' => 'nav-link']) !!}</li>
                @endif
            </ul>
        </div>
    </nav>
</header>