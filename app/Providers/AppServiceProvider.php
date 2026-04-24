<?php

namespace App\Providers;

use App\Models\Goal;
use App\Models\Task;
use App\Observers\GoalObserver;
use App\Observers\TaskObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //「データの変化」と「ポイント加算の自動処理」をつなぐ
        Task::observe(TaskObserver::class);
        Goal::observe(GoalObserver::class);

        // パスワードリセットメールの文面を完全に書き換える
        ResetPassword::toMailUsing(function ($notifiable, $token) {
            $resetUrl = route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ]);

            return (new MailMessage)
                ->subject('【' . config('app.name') . '】パスワード再設定のお知らせ')
                ->greeting('こんにちは。')
                ->line('タスク提案アプリ「' . config('app.name') . ' 」にて、')
                ->line('パスワード再設定のリクエストが行われました。')
                ->line('下記のボタンから、新しいパスワードを設定してください。')
                ->line('このリンクの有効期限は 60分 です。')
                ->action('パスワードをリセットする', $resetUrl)
                ->line('本手続きにお心当たりがない場合、')
                ->line('第三者による操作の可能性があります。')
                ->line('その場合は、本メールを破棄し、再設定操作は行わないでください。')
                ->line('安全のため、定期的なパスワード変更を推奨いたします。')
                ->salutation(
                    "――――――――\n" . 
                    config('app.name') . "\n" . 
                    "Email：" . config('app.support_email') . "\n" .
                    "URL：" . config('app.url')
                );
        });
    }
}