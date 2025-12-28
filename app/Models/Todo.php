<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Todo extends Model
{ // Tambahkan 'assigned_by_id' dan 'deadline'
    protected $fillable = ['title', 'description', 'completed_at', 'user_id', 'assigned_by_id', 'deadline'];

    protected $casts = [
        'completed_at' => 'datetime',
        'deadline' => 'datetime', // Casting deadline juga
    ];

    // Relasi 1: Penerima Tugas
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi 2: Pembuat Tugas (Admin)
    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by_id');
    }
}
