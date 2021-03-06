<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;    // 追加

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
        if (\Auth::check()) { // 認証済みの場合
        //ユーザを取得
            $user = \Auth::user();   
            // ユーザの投稿の一覧を作成日時の降順で取得
            $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);

            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
            
            return view('tasks.index',$data);
        }

        // Welcomeビューでそれらを表示
        return view('welcome', $data);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
     //getでtasks/createにアクセスされた場合の「新規登録画面表示処理」
    public function create()
    {
        
            
        $task = new Task;
        
        //メッセージ作成ビューを表示
        return view('tasks.create',[
            'task' => $task,
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
    
    
    
            
        
       //バリデーション
       $request->validate([
           'status'  => 'required|max:10', //追加
           'content' => 'required|max:255'
           ]);
       
        //タスクを作成
        $task = new Task;
        $task->status = $request->status; //追加
        $task->content = $request->content;
        $task->user_id = \Auth::user()->id;
        $task->save();
        
        //前のURLへリダイレクトさせる
        return redirect('/');
        
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        
        //idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        
        //認証済みデータが投稿の所有者である場合に編集する
        if (\Auth::id() === $task->user_id) {
        
        
        //タスク詳細ビューでそれを表示
        return view('tasks.show',[
            'task' => $task,
        ]);
      }
        //トップページへリダイレクトさせる
        return redirect('/');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    
    {
        
        
        //idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        
        //認証済みデータが投稿の所有者である場合に編集する
        if (\Auth::id() === $task->user_id) {
        
        //タスク編集ビューでそれを表示
        return view('tasks.edit',[
            'task' => $task,
            ]);
        
        }
        //トップページへリダイレクトさせる
        return redirect('/');
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
        //バリデーション
        $request->validate([
            'status' => 'required|max:10',
            'content' => 'required|max:255',
            
        ]);
        
 
            
        
        
        //idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        
        //認証済みデータが投稿の所有者である場合に更新する
        if (\Auth::id() === $task->user_id) {
        
        
        //メッセージを更新
        $task->status = $request->status; //追加
        $task->content = $request->content;
        $task->user_id = \Auth::user()->id;//追加
        $task->save();
        
        }
        
        //トップページへリダイレクトさせる
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        
        //認証済みユーザが投稿の所有者である場合に削除する
        if (\Auth::id() === $task->user_id) {
            $task->delete();
        }
        
        //前のURLへリダイレクトさせる
        return redirect('/');
    }
}
