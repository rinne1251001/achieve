<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
{
    /**
     * 会員登録後のレスポンス（リダイレクト先）を定義
     */
    public function toResponse($request)
    {
        // 会員登録後は /chat へリダイレクトさせる
        return redirect('/chat');
    }
}