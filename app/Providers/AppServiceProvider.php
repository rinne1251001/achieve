<?php

namespace App\Providers;

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
        // パスワードリセットメールの文面を完全に書き換える
        ResetPassword::toMailUsing(function ($notifiable, $token) {
            $resetUrl = url(config('app.url').route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

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
                ->salutation("――――――――\n" . 
                 config('app.name') . "\n" . 
                 "Email：noreply@achieve-on-step.sakuraweb.com\n" . 
                 "URL：https://achieve-on-step.sakuraweb.com");
        });
    }
}