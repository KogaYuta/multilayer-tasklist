<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Project;

class ProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
        if (\Auth::check()) {
            $user = \Auth::user();
            $projects = $user->projects()->orderBy('created_at', 'desc')->get();
            
            $data = [
                'user' => $user,
                'projects' => $projects,
            ];
            
        }
        
        return view('projects.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $project = new Project;
        
        return view('projects.create', [
            'project' => $project,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        if (\Auth::check()) {
            $this->validate($request, [
                'content' => 'required|max:191',
            ]);
            
            $request->user()->projects()->create([
                'content' => $request->content,
            ]);
            
            // 今作ったprojectを取得
            $user = \Auth::user();
            $projects = $user->projects()->orderBy('created_at', 'desc')->get();
            $project = $projects[0];
            
            $project->tasks()->create([
                'parent_id'=>null,
                'project_flag'=>1,
                'content'=>$request->content,
                'selected'=>0
            ]);
            
            // taskを更新したのでもう一度取得
            $projects = $user->projects()->orderBy('created_at', 'desc')->get();
            $project = $projects[0];
            
            // parent_idを代入
            $tasks = $project->tasks()->orderBy('created_at', 'desc')->get();
            $task = $tasks[0];
            $task->parent_id=$task->id;
            $task->save();
            
            return redirect()->action('ProjectsController@index');
            
            // 今作ったprojectと同じcontentのtaskを作るためにTasksController@storeへ
            // return redirect()->action('TasksController@store',['project' => $project]);
            
        } else {
            return redirect('/');
        }
        
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (\Auth::check()) {
            
            $user = \Auth::user();
            $project = $user->projects()->find($id);
            
            return view('projects.edit', [
                'project' => $project  
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (\Auth::check()) {
            $user = \Auth::user();
            $project = $user->projects()->find($id);
            
            // update処理
            $project->content = $request->content;
            $project->save();
            
            //親taskの名前も変更する
            $tasks = $project->tasks()->get();
            $task = 0;
            foreach($tasks as $t) {
                if ($t->project_flag == true) {
                    $task = $t;
                }
            }
            
            $taskNew = $project->tasks()->get()->find($task->id);
            $taskNew->content = $request->content;
            $taskNew->save();
            
            return redirect()->action('ProjectsController@index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $project = Project::find($id);
        if ($project->user_id === \Auth::id()) {
            $project->delete();
        }
        
        return redirect()->action('ProjectsController@index');
    }
}
