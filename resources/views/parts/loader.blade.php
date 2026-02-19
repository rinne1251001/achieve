<div id="common-loader" style="display: none;">
    <div class="loader-overlay">
        <div class="spinner"></div>
        <p class="loader-text">処理中...</p>
    </div>
</div>

<style>
    #common-loader {
        position: fixed;
        top: 0; left: 0;
        width: 100vw; height: 100vh;
        background: rgba(0, 0, 0, 0.5);
        z-index: 10000;
        display: none; /* JSで制御 */
        justify-content: center;
        align-items: center;
    }
    .loader-overlay {
        text-align: center;
        color: white;
    }
    .spinner {
        width: 50px; height: 50px;
        border: 5px solid rgba(255, 255, 255, 0.3);
        border-top: 5px solid #fff;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 10px;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<script>
    // どこからでも呼び出せるグローバル関数を定義
    window.Loader = {
        show: function() {
            document.getElementById('common-loader').style.display = 'flex';
        },
        hide: function() {
            document.getElementById('common-loader').style.display = 'none';
        }
    };
</script>