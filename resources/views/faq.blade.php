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
                        タスク→1pt
                        ゴール→10pt

                        各レベル必要なポイント
                        2+5(レベル-1)
                    </p>
                </div>
            </div>

            <div class="faq_item">
                <div style="display: flex; justify-content: space-between; align-items: center; cursor: pointer; gap: 8px;" onclick="toggleFaq(this)">
                    <div style="font-size: 1.2em; font-weight: bold; display: flex; align-items: center;"><span class="mypage_numeral" style="color: var(--accent-color); font-size: 2.5em; padding-right: 15px;">Q</span>ポイントの貯め方を教えてください</div>
                    <div class="faq_plus_btn"><span class="faq_plus_line"></span><span class="faq_plus_line"></span></div>
                </div>
                <div class="faq_answer">
                    <p>
                        タスク→1pt
                        ゴール→10pt

                        各レベル必要なポイント
                        2+5(レベル-1)
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