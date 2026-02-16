@extends('layouts.app')
@section('title', 'Q&A集')
@section('content')

<div class="wrapper3">
    <aside class="sidebar"></aside>
    <main style="padding: 0 clamp(10px, 5vw, 30px);">

        <h2 class="mypage_numeral" style="text-align: center; color: var(--base-color); font-size: 6em; margin: 0;">Q&A</h2>
        <p style="text-align: center; font-size: 1.5em; font-weight: bold; margin: 0;">よく寄せられるご質問</p>

        <div style="padding: 50px 0;">

            <div class="faq_item">
                <div style="display: flex; justify-content: space-between; align-items: center; cursor: pointer; gap: 8px;" onclick="toggleFaq(this)">
                    <div style="font-size: 1.2em; font-weight: bold; display: flex; align-items: center;"><span class="mypage_numeral" style="color: var(--accent-color); font-size: 2.5em; padding-right: 15px;">Q</span>ポイントの貯め方を教えてください</div>
                    <div class="faq_plus_btn"><span class="faq_plus_line"></span><span class="faq_plus_line"></span></div>
                </div>
                <div class="faq_answer">
                    <p>
                        タスク達成で1pt
                        ゴール達成で10pt獲得できます。<br>

                        各レベルで必要なポイントは以下の式で計算できます。<br>
                        2+5(レベル-1)
                    </p>
                </div>
            </div>

            <div class="faq_item">
                <div style="display: flex; justify-content: space-between; align-items: center; cursor: pointer; gap: 8px;" onclick="toggleFaq(this)">
                    <div style="font-size: 1.2em; font-weight: bold; display: flex; align-items: center;"><span class="mypage_numeral" style="color: var(--accent-color); font-size: 2.5em; padding-right: 15px;">Q</span>タスク管理ページの操作を教えてください</div>
                    <div class="faq_plus_btn"><span class="faq_plus_line"></span><span class="faq_plus_line"></span></div>
                </div>
                <div class="faq_answer">
                    <p>
                        タスク管理画面の操作<br>
                        ・「ゴールを追加する」をクリックすることでゴールを設定できます。<br>
                        ・「自分で追加する」をクリックすることでそのゴールにタスクを追加できます。<br>
                        ・タスク名をクリックするとタスクの詳細を確認でき、編集も行うことができます。<br>
                        ・タスク名の左のチェックボックスをクリックすることでタスクの完了が行えます。<br>
                        ・タスク名の右のごみ箱のマークをクリックすることでタスクの削除が行えます。<br>
                        ・ゴール名をクリックすることでゴールの詳細ページに移動します。
                    </p>
                </div>
            </div>

            <div class="faq_item">
                <div style="display: flex; justify-content: space-between; align-items: center; cursor: pointer; gap: 8px;" onclick="toggleFaq(this)">
                    <div style="font-size: 1.2em; font-weight: bold; display: flex; align-items: center;"><span class="mypage_numeral" style="color: var(--accent-color); font-size: 2.5em; padding-right: 15px;">Q</span>ゴール詳細ページの操作を教えてください</div>
                    <div class="faq_plus_btn"><span class="faq_plus_line"></span><span class="faq_plus_line"></span></div>
                </div>
                <div class="faq_answer">
                    <p>
                        ゴール詳細ページの操作<br>
                        ・タスク名をクリックするとタスクの詳細を確認でき、編集も行うことができます。<br>
                        ・タスク名の左のチェックマークをクリックすることでタスクの完了が行えます。<br>
                        ・タスク名の右のごみ箱のマークをクリックすることでタスクの削除が行えます。<br><br>
                        画面右下のメニューの操作<br>
                        ・＋ボタンをクリックすることでタスクの追加を行えます。<br>
                        ・チェックマークのボタンをクリックすることでゴールの完了を行えます。<br>
                        ・ペンマークのボタンをクリックすることでゴールの編集を行えます。
                    </p>
                </div>
            </div>

            <div class="faq_item">
                <div style="display: flex; justify-content: space-between; align-items: center; cursor: pointer; gap: 8px;" onclick="toggleFaq(this)">
                    <div style="font-size: 1.2em; font-weight: bold; display: flex; align-items: center;"><span class="mypage_numeral" style="color: var(--accent-color); font-size: 2.5em; padding-right: 15px;">Q</span>タスク・ゴールの設定の注意点を教えてください</div>
                    <div class="faq_plus_btn"><span class="faq_plus_line"></span><span class="faq_plus_line"></span></div>
                </div>
                <div class="faq_answer">
                    <p>
                        注意点<br>
                        ・タスク名は最大５０文字、ゴール名は最大３５文字までとなっています。<br>
                        ・同時に設定できるゴールは最大３つまでです。<br>
                        ・完了したタスク・ゴールは完了前に戻せません。
                    </p>
                </div>
            </div>
            
            <div class="faq_item">
                <div style="display: flex; justify-content: space-between; align-items: center; cursor: pointer; gap: 8px;" onclick="toggleFaq(this)">
                    <div style="font-size: 1.2em; font-weight: bold; display: flex; align-items: center;"><span class="mypage_numeral" style="color: var(--accent-color); font-size: 2.5em; padding-right: 15px;">Q</span>画面の色の変え方を教えてください</div>
                    <div class="faq_plus_btn"><span class="faq_plus_line"></span><span class="faq_plus_line"></span></div>
                </div>
                <div class="faq_answer">
                    <p>
                        右上のメニューより設定画面を開き、<br>
                        テーマカラーの欄より好きな色を選び、<br>
                        保存を押すことで画面の色を変更できます。
                    </p>
                </div>
            </div>

            <div class="faq_item">
                <div style="display: flex; justify-content: space-between; align-items: center; cursor: pointer; gap: 8px;" onclick="toggleFaq(this)">
                    <div style="font-size: 1.2em; font-weight: bold; display: flex; align-items: center;"><span class="mypage_numeral" style="color: var(--accent-color); font-size: 2.5em; padding-right: 15px;">Q</span>設定した名前やメールアドレスの変更方法を教えてください</div>
                    <div class="faq_plus_btn"><span class="faq_plus_line"></span><span class="faq_plus_line"></span></div>
                </div>
                <div class="faq_answer">
                    <p>
                        右上のメニューより設定画面を開き、<br>
                        プロフィールの欄より新しい名前・メールアドレスを入力し、<br>
                        「保存」を押すことで登録情報を変更できます。
                    </p>
                </div>
            </div>

            <div class="faq_item">
                <div style="display: flex; justify-content: space-between; align-items: center; cursor: pointer; gap: 8px;" onclick="toggleFaq(this)">
                    <div style="font-size: 1.2em; font-weight: bold; display: flex; align-items: center;"><span class="mypage_numeral" style="color: var(--accent-color); font-size: 2.5em; padding-right: 15px;">Q</span>パスワードの変更方法を教えてください</div>
                    <div class="faq_plus_btn"><span class="faq_plus_line"></span><span class="faq_plus_line"></span></div>
                </div>
                <div class="faq_answer">
                    <p>
                        右上のメニューより設定画面を開き、<br>
                        パスワード変更の欄より現在のパスワードと新しいパスワードを入力し、<br>
                        「パスワードを更新」を押すことで登録情報を変更できます。
                    </p>
                </div>
            </div>

        </div>

    </main>
    <aside class="sidebar"></aside>
</div>

@endsection
@push('scripts')
<script>
    function toggleFaq(element) {
        const parent = element.closest('.faq_item');
        parent.classList.toggle('open');
    }
</script>
@endpush