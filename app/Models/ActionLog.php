<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActionLog extends Model
{
    protected $table = 'action_logs';

    protected $primaryKey = 'id_action_log';

    protected $fillable = [
        'id_user',
        'user_name',
        'user_role',
        'aktivitas',
        'detail',
        'modul',
        'ip_address',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public static function record(?User $user, string $aktivitas, string $modul = 'Sistem', ?string $detail = null, string $status = 'Berhasil', ?string $ip = null): self
    {
        return static::create([
            'id_user' => $user?->id_user,
            'user_name' => $user?->nama_lengkap ?? $user?->username ?? 'Sistem',
            'user_role' => $user?->role ? ucfirst($user->role) : 'Sistem',
            'aktivitas' => $aktivitas,
            'modul' => $modul,
            'detail' => $detail,
            'ip_address' => $ip ?? request()->ip() ?? '127.0.0.1',
            'status' => $status,
        ]);
    }
}
