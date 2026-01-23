<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use Illuminate\Support\Facades\Auth;

class GoalController extends Controller
{
    public function show(Goal $goal)
    {
        // ログイン中のユーザーIDと一致するか確認（セキュリティ）
        if ($goal->user_id !== Auth::id()) {
            abort(403);
        }

        // 紐づくタスクも一緒に読み込む
        $goal->load('tasks');

        return view('goals_detail', compact('goal'));
    }
}