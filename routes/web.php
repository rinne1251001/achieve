<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\ChatController;

Route::view('/', 'top')->name('top');
Route::view('/chat_test', 'chat_test')->name('chat_test');
Route::view('/chat_movingtest', 'chat_movingtest')->name('chat_mobingtest');
Route::view('/faq', 'faq')->name('faq');

/* ログインしていないと見れないページ */
Route::middleware(['auth'])->group(function () {
    Route::get('/mypage', [GoalController::class,'index'])->name('mypage');
    Route::view('/mypage_test', 'mypage_test')->name('mypage_test');
    Route::view('/setting', 'setting')->name('setting');
    Route::get('/task', [GoalController::class, 'taskPage'])->name('task');
    
    Route::get('/mygoal/{goal}', [GoalController::class, 'show'])->name('goals.show');
    Route::patch('/tasks/{task}/check', [GoalController::class, 'check'])->name('tasks.check');
    Route::get('/calendar', [GoalController::class, 'calendar'])->name('calendar');
    Route::delete('/tasks/{task}', [GoalController::class, 'destroy']); //タスク削除    
    Route::post('/tasks', [GoalController::class, 'store']);// タスクの新規登録   
    Route::patch('/tasks/{task}', [GoalController::class, 'taskupdate']);// タスクの更新（編集保存用）
    Route::patch('/goals/{goal}', [GoalController::class, 'goalupdate'])->name('goals.update');// ゴールの更新用ルート
    Route::post('/goals', [GoalController::class, 'storeGoal'])->name('goals.store');//ゴール新規作成
    Route::patch('/goals/{goal}/check', [GoalController::class, 'goalCheck'])->name('goals.check');// ゴールの完了（達成）フラグ更新用

    /* チャットテスト用 */
    Route::get('/chat_test2', [App\Http\Controllers\ChatController::class, 'index']);
    Route::post('/chat_test2', [App\Http\Controllers\ChatController::class, 'chat_test2'])->name('chat_test2');
    Route::post('/chat_test2_save', [ChatController::class, 'saveProposedGoal']);
});


/*
                ～ルートの書き方～

    【　Route::view('/〇〇', '△△')->name('□□');　】

    〇〇に表示させたいURL名を書く
    例）'/achieve'と書いたら、http://127.0.0.1:8000/achieve というURLになる

    △△にファイルがどこに格納されているのかを書く
    例）resource/views/articlesにあるなら'articles/achive'と書く

    □□にどんな名前で管理したいのかを書く
    ファイル名と同じにすると管理が楽
    例）->name('achieve')なら、<a href="{{ route('achieve') }}>で遷移できるようになる

*/

/*
                   ～フォルダ/ファイルの場所～

    ・画像
    →public/imagesフォルダに入れる

    ・css
    →public/cssフォルダに入れる

    ・記事（topページとか）
    →resource/viewフォルダに入れる
    →routes/web.phpに表示させたいURL

*/

/*
                   ～Laravelの書き方～

    画像は<img src="{{ asset('images/〇〇.jpg') }}">、
    ハイパーリンクは<a href="{{ route('〇〇') }}">の形式

    記事を見るときは、ターミナルで「php artisan serve」と打って、
    表示されたURLをクリック

*/

/*
                   ～GitHubへの上げ方～

    １．左側にあるソース管理(Ctrl+Shift+G)※上から３つ目 を選択
    ２．「変更」の右隣りにある「＋」ボタン（すべての変更をステージ）をクリック
    ３．メッセージになんか一言を入れる
    ４．「✓コミット」をクリック
    ５．「グラフ」の右隣りにある「プッシュ」ボタン※一番右にある をクリック

                   ～他の人のGitHubの変更をこっちにも反映させる方法～
    １．「グラフ」の右隣りにある「プル」ボタン※真ん中にある をクリック
*/