<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    //一括保存許可リスト
    protected $fillable = [
        'name',
        'email',
        'password',
        'theme_color',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    //隠し項目
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    //データ型の設定
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'point'             => 'integer',
        ];
    }

    //1人のユーザーは複数のGoalを持つ
    public function goals(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Goal::class);
    }

    //1人のユーザーはiつの性格診断結果を持つ
    public function analysis(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(UserAnalysis::class);
    }

    //レベルアップ計算
    public function calculateLevel(int $totalPoints): int
    {
        $totalPoints = max(0, $totalPoints);
        if ($totalPoints < 2) return 1;
        return (int) floor(($totalPoints - 2) / 5) + 2;
    }
}
