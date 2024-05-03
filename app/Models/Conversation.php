<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Conversation extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Conversation $conversation) {
            $conversation->uuid = Str::uuid();
        });
    }

    public function messages(): HasMany
    {
        return  $this->hasMany(Message::class);
    }
}
