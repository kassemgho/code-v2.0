<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Administrator extends Model
{
    use HasFactory;
    protected $fillable = ['user_id' ,'phone_number', 'date_of_birth'];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class);
    }
    public function problems(): HasMany
    {
        return $this->hasMany(Problem::class);
    }
}
