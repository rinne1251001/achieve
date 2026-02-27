window.getTimeoutSignal = function(seconds = 10) { //設定するタイムアウト時間を秒単位で入力
    const controller = new AbortController();
    setTimeout(() => {
        controller.abort(new DOMException('timeout', 'AbortError'));
    }, seconds * 1000);
    return controller.signal;
};

window.parseError = function(err) {
    if (err.name === 'AbortError') {
        return '通信に時間がかかりすぎているようです。電波の良い場所で再度お試しください。';
    }
    // タイムアウト以外は、投げられたエラーメッセージをそのまま使う
    return err.message || '通信エラーが発生しました。';
};