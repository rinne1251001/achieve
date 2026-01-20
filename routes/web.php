<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'top')->name('top');


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