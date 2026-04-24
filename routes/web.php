<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MypageController;

Route::view('/', 'top')->name('top');
Route::view('/faq', 'faq')->name('faq');

/* ログインしていないと見れないページ */
Route::middleware(['auth'])->group(function () {
    // 読み取り系（レートリミットなし）
    Route::get('/mypage',           [MypageController::class, 'index'])->name('mypage');
    Route::get('/chat',             [ChatController::class, 'index'])->name('chat'); // ← throttleから除外
    Route::get('/task',             [TaskController::class, 'index'])->name('task');
    Route::get('/mygoal/{goal}',    [GoalController::class, 'show'])->name('goals.show');
    Route::view('/setting', 'setting')->name('setting');

    // 書き込み系（レートリミット付き）
    Route::middleware('throttle:60,1')->group(function () {
        // タスク
        Route::resource('tasks', TaskController::class)->only(['store', 'update', 'destroy']);
        Route::patch('/tasks/{task}/check', [TaskController::class, 'check'])->name('tasks.check');

        // ゴール
        Route::resource('goals', GoalController::class)->only(['store', 'update']);
        Route::patch('/goals/{goal}/check', [GoalController::class, 'check'])->name('goals.check');

        // チャット
        Route::post('/chat',      [ChatController::class, 'chat']);
        Route::post('/chat_save', [ChatController::class, 'saveProposedGoal'])->name('chat.save');
        Route::get('/check-goal-limit', [ChatController::class, 'checkGoalLimit'])->name('chat.goal-limit');
    });
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