<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Illuminate\Support\Facades\DB;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
            'theme_color' => ['nullable', 'string', 'max:20'],
        ])->validate();

        return DB::transaction(function () use ($input) {
            $user = User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
                'theme_color' => $input['theme_color'] ?? 'aqua',
            ]);

            $this->createTutorialData($user);
            return $user;
        });
    }

    private function createTutorialData(User $user)
    {
        $goal = $user->goals()->create([
            'goal' => 'チュートリアル',
        ]);

        $goal->tasks()->createMany([
            [
                'task' => 'AIとチャットでゴールを作ってみよう',
                'detail' => "右上のメニューボタンからAIチャットに飛んで\nAIと一緒に自分なりのゴールを作ってみよう",
                'target_date' => now()->addDays(2)->format('Y-m-d'),
            ],
            [
                'task' => 'タスクにチェックを入れて完了にしてみよう',
                'detail' => "左のチェックボックスにチェックを入れると\nタスクを完了したことにできるよ",
                'target_date' => now()->addDays(2)->format('Y-m-d'),
            ],
        ]);
    }
}
