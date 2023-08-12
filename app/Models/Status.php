<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $fillable = ['statusable_type', 'statusable_id', 'status'];

    protected static function booted()
    {
        static::creating(function ($status) {
            $status->user_id = auth()->id();
        });
    }

    public function statusable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
