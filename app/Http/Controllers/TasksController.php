<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;

class TasksController extends Controller
{
    // getでtasks/にアクセスされた場合の「一覧表示処理」
    public function index()
    {
        $tasks = Task::all();
        return view('tasks.index', [
            'tasks' => $tasks,    
        ]);
    }

    // getでtasks/createにアクセスされた場合の「新規登録画面表示処理」
    public function create()
    {
        //
    }

    // postでtasks/にアクセスされた場合の「新規登録処理」
    public function store(Request $request) //Request $request
    {
        //タスクを追加する処理
        //['project_id', 'parent_id', 'project_flag', 'content', 'selected']を送信する
        // jsonメソッドの第一引数が$.ajaxのdoneメソッドのコールバック関数の第一引数に入る
        $task = $request->task;
        
        Task::create([
            'project_id'=>$task['project_id'],
            'parent_id'=>$task['parent_id'], 
            'project_flag'=>$task['project_flag'],
            'content'=>$task['content'],
            'selected'=>$task['selected']
        ]);
        
        $tasks = Task::all();
        // return response()->json($tasks, \Illuminate\Http\Response::HTTP_OK);
        return $tasks;
    }

    // getでtasks/idにアクセスされた場合の「取得表示処理」
    public function show($id)
    {
        //
    }

    // getでtasks/id/editにアクセスされた場合の「更新画面表示処理」
    public function edit($id)
    {
        //
    }

    // putまたはpatchでtasks/idにアクセスされた場合の「更新処理」
    public function update(Request $request, $id)
    {
        //
    }

    // deleteでtasks/idにアクセスされた場合の「削除処理」
    public function destroy($id)
    {
        //
    }
    
    
    // ajaxでアクセスした場合のメソッド
    // postでajax/tasks/にアクセスされた場合の「新規登録処理」
    public function ajaxStore(Request $request) //Request $request
    {
        //タスクを追加する処理
        //['project_id', 'parent_id', 'project_flag', 'content', 'selected']を送信する
        // jsonメソッドの第一引数が$.ajaxのdoneメソッドのコールバック関数の第一引数に入る
        $task = $request->task;
        
        Task::create([
            'project_id'=>$task['project_id'],
            'parent_id'=>$task['parent_id'], 
            'project_flag'=>$task['project_flag'],
            'content'=>$task['content'],
            'selected'=>$task['selected']
        ]);
        
        $tasks = Task::all();
        // return response()->json($tasks, \Illuminate\Http\Response::HTTP_OK);
        return $tasks;
    }
}