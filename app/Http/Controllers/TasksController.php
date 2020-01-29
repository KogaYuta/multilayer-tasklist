<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;
use App\User;

class TasksController extends Controller
{
    // 全てのprojectと各projectに紐付く全てのtaskを取得する
    public function index()
    {
        $data = [];
        if (\Auth::check()) {
            $user = \Auth::user();
            
            $projects = $user->projects()->with(['tasks'])->get();
            
            // 末端taskのみ取得する
            // 末端taskのみが格納される連想配列
            // keyは指標(project_id)ではない
            $tasksObject = [];
            foreach ($projects as $project) {
                $tasks = $project->tasks()->get();
                $deleteIndex = array();
                // 各taskが子を持つか検索
                foreach ($tasks as $key=> $task) {
                    $flag = false;
                    // $taskが子を持つかどうか検索
                    foreach ($tasks as $t) {
                        if ($task->id == $t->parent_id){
                            $flag = true;
                        }
                    }
                    // taskが子を持つ場合
                    if ($flag) {
                        $deleteIndex[]=$key;
                    }
                }
                
                // deleteIndexに存在するtaskを削除
                // その指標は欠番になることに注意
                foreach($deleteIndex as $ind) {
                    unset($tasks[$ind]);
                }
                
                // tasksをidを基準に昇順にする
                
                // selectedされたものは下に表示する
                
                 
                $tasksObject[$project->id]=$tasks;
                
            }
            
            // 各projectの末端タスクの達成率を計算
            // keyはproject_id
            // valueは末端タスクの達成率
            $tasksCompleted = [];
            // 各projectの達成率
            // 完了した末端タスク数 / 全体の末端タスク数
            foreach ($projects as $project) {
                $tasks = $project->tasks()->get();
                // 完了済みの末端タスク数の取得
                $counter = 0;
                foreach ($tasks as $task) {
                    if ($task->selected) {
                        $counter = $counter + 1;
                    }
                }
                
                // $n = count($tasksObject[$project->id]);
                $tasks = $project->tasks()->get();
                $n = count($tasks);
                
                // ゼロ除算を防ぐ
                if ($n == 0) {
                    $n = 1;
                }
                
                $completeRatio = (int)($counter / $n * 100);
                $tasksCompleted[$task->project_id]=$completeRatio;
            }
            
            $data = [
                'user' => $user,
                'projects' => $projects,
                'tasksObject' =>$tasksObject,
                'tasksCompleted' =>$tasksCompleted,
            ];
            
        }
        return view('welcome', $data);
    }
    
    // タスクツリー作成画面に移動する
    public function tree(Request $request) {
        // $id : projectのid
        // projectに紐付くtaskを全て取得
        
        // $user = \Auth::user();
        // $project_id = $request->id;
        // $project = $user->projects()->get()->find($project_id);
        // $tasks = $project->tasks()->get();
        
        // $selected_parent_id = array();
        // // selected=1のタスクの親idを取得する
        // foreach($tasks as $task){
        //     if ($task->selected == true) {
        //         // selected=1のタスクの親を取得し代入
        //         if (in_array($task->parent_id, $selected_parent_id) == false) {
        //             $selected_parent_id[]=$task->parent_id;
        //         }
        //     }
        // }
        
        $loop_flag = true;
        $count = 0;
        while ($loop_flag) {
            $loop_flag = false;
            
            $user = \Auth::user();
            $project_id = $request->id;
            $project = $user->projects()->get()->find($project_id);
            $tasks = $project->tasks()->get();
            
            // new version
            $selected_parent_id = array();
            // selected=1のタスクの親idを取得する
            foreach($tasks as $task){
                if ($task->selected == true) {
                    // selected=1のタスクの親を取得し代入
                    if (in_array($task->parent_id, $selected_parent_id) == false) {
                        $selected_parent_id[]=$task->parent_id;
                    }
                }
            }
            
            $selected_parent_id = array();
            // selected=1のタスクの親idを取得する
            foreach($tasks as $task){
                if ($task->selected == true) {
                    // selected=1のタスクの親を取得し代入
                    if (in_array($task->parent_id, $selected_parent_id) == false) {
                        $selected_parent_id[]=$task->parent_id;
                    }
                }
            }
            
            // $selected_parent_idに格納された親taskについてforeach
            // taskの子タスクが全てselected=1ならば、親taskもselected=1に設定する
            foreach($selected_parent_id as $parent_id){
                $debug_array[] = $parent_id;
                // $parent_idをparent_idにもつtaskを格納
                $tasksChildren = array();
                // $parent_idをparent_idにもつtaskを取得
                foreach($tasks as $task) {
                    // 根taskは対象外
                    if ($parent_id == $task->parent_id) {
                        if ($task->parent_id != $task->id) {
                            $tasksChildren[]=$task;
                        }
                    }
                }
                // 子タスクが１つでもselected=0ならば、falseになる
                $flag = true;
                foreach($tasksChildren as $task) {
                    if ($task->selected == false) {
                        $flag = false;
                    }
                }
                
                // 現在の親taskを取得
                $task = $project->tasks()->get()->find($parent_id);
                //もし子タスクが全てselected=1ならば、その親taskのselectedも1にする
                if ($flag) {
                    $selected = $task->selected;
                    $task->selected = true;
                    // 元々はselected=0なのに今回変更される場合
                    // 他のtaskも変更が発生する可能性があるので、次もloopを回す
                    if ($selected == false) {
                        $loop_flag = true;
                    }
                //１つでもselected=0の子タスクがあれば、親taskのselected=0
                } else {
                    $task->selected = false;
                }
                // taskの変更を反映
                $task->save();
            }
            $count++;
        }
        // dd($count);
        
        
        // $selected_parent_id = array();
        // // selected=1のタスクの親idを取得する
        // foreach($tasks as $task){
        //     if ($task->selected == true) {
        //         // selected=1のタスクの親を取得し代入
        //         if (in_array($task->parent_id, $selected_parent_id) == false) {
        //             $selected_parent_id[]=$task->parent_id;
        //         }
        //     }
        // }
        
        // // $selected_parent_idに格納された親taskについてforeach
        // // taskの子タスクが全てselected=1ならば、親taskもselected=1に設定する
        // foreach($selected_parent_id as $parent_id){
        //     $debug_array[] = $parent_id;
        //     // $parent_idをparent_idにもつtaskを格納
        //     $tasksChildren = array();
        //     // $parent_idをparent_idにもつtaskを取得
        //     foreach($tasks as $task) {
        //         // 根taskは対象外
        //         if ($parent_id == $task->parent_id) {
        //             if ($task->parent_id != $task->id) {
        //                 $tasksChildren[]=$task;
        //             }
        //         }
        //     }
        //     // 子タスクが１つでもselected=0ならば、falseになる
        //     $flag = true;
        //     foreach($tasksChildren as $task) {
        //         if ($task->selected == false) {
        //             $flag = false;
        //         }
        //     }
            
        //     // 現在の親taskを取得
        //     $task = $project->tasks()->get()->find($parent_id);
        //     //もし子タスクが全てselected=1ならば、その親taskのselectedも1にする
        //     if ($flag) {
        //         $task->selected = true;
        //     //１つでもselected=0の子タスクがあれば、親taskのselected=0
        //     } else {
        //         $task->selected = false;
        //     }
        //     // taskの変更を反映
        //     $task->save();
        //     // 親タスクの親タスクも変更するので、末尾に追加
        //     array_push($selected_parent_id,$task->parent_id);
        //     $selected_parent_id = array_values($selected_parent_id);
        // }
        
        $tasks = $project->tasks()->get();
        
        $tasksString = json_encode($tasks);
        
        return view('tasks.tree',[
            'tasks' => $tasks,
            'tasksString' => $tasksString 
        ]);
    }

    // getでtasks/createにアクセスされた場合の「新規登録画面表示処理」
    public function create()
    {
        //
    }

    // Projectを作成した時に同名の親タスク(ツリーの根)を作成する
    public function store(Request $request) //Request $request
    {
        
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
    // getでajax/tasks/にアクセスされた場合の処理
    public function ajaxIndex(Request $request)
    {
        if (\Auth::check()) {
            $user = \Auth::user();
            $projects = $user->projects()->get();
            return $projects;
        } else {
            $users = User::all();
            return $users;
        }
    }
    
    // postでajax/tasks/にアクセスされた場合の処理
    public function ajaxCRUD(Request $request) //Request $request
    {
        //タスクを追加する処理
        //['project_id', 'parent_id', 'project_flag', 'content', 'selected']を送信する
        
        if (\Auth::check()) {
            $status = $request->status;
        
            // タスクを新規登録する処理
            if ($status === 'create') {
                
                $user = \Auth::user();
                $task = $request->task;
                $project_id = $task['project_id'];
                $project = $user->projects()->get()->find($project_id);
                
                $project->tasks()->create([
                    'parent_id'=>$task['parent_id'], 
                    'project_flag'=>$task['project_flag'],
                    'content'=>$task['content'],
                    'selected'=>$task['selected']
                ]);
                
                // 作成後の全タスクを返す
                $tasks = $project->tasks()->orderBy('id', 'asc')->get();
                return $tasks;
                
            }  else if ($status === 'delete') {
                $id = $request->id;
                $id = (int)$id;
                $project_id = $request->project_id;
                $project_id = (int)$project_id;
                
                $user = \Auth::user();
                $project = $user->projects()->get()->find($project_id);
                $task = $project->tasks()->get()->find($id);
                
                // タスクを削除
                $task->delete();
                
                // 作成後の全タスクを返す
                $tasks = $project->tasks()->orderBy('id', 'asc')->get();
                return $tasks;
                
                
                
            } else if ($status === 'update') {
                
                $id = $request->id;
                $id = (int)$id;
                $project_id = $request->project_id;
                $project_id = (int)$project_id;
                $content = $request->content;
                
                $user = \Auth::user();
                $project = $user->projects()->get()->find($project_id);
                $task = $project->tasks()->get()->find($id);
                
                // タスクを更新
                $task->content = $content;
                $task->save();
                
                
                // 根タスクを編集した場合、projectも変更する
                if ($task->project_flag) {
                    $project->content = $content;
                    $project->save();
                }
                
                // 作成後の全タスクを返す
                $tasks = $project->tasks()->orderBy('id', 'asc')->get();
                return $tasks;
                
            } else if ($status === 'select') {
                $id = $request->id;
                $project_id = $request->project_id;
                
                $user = \Auth::user();
                $project = $user->projects()->get()->find($project_id);
                $task = $project->tasks()->get()->find($id);
                
                // タスクの完了状態を更新
                if ($task->selected) {
                    $task->selected = false;
                } else {
                    $task->selected = true;
                }
                
                $task->save();
                
                // 作成後の全タスクを返す
                $tasks = $project->tasks()->orderBy('id', 'asc')->get();
                return $tasks;
            }
        }
    }
}

// default以外のノードを削除する
// use App\Task;
// Task::all();
// $a = range(108,112);
// $task = Task::find($a);
// foreach($task as $t){$t->delete();}
// Task::all();

// 親ノード以外削除
// use App\User;
// use App\Project;
// use App\Task;
// $user = User::find(2);
// $projects = $user->projects()->get();
// $project = $projects[1];
// $tasks = $project->tasks()->get();
// $a = range(109,136);
// $tasks = $project->tasks()->get()->find($a);
// foreach ($tasks as $task) {$task->delete();}
// $project->tasks()->get();