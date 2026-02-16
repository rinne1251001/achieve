<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAnalysis extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type_key',
        'type_bit',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
