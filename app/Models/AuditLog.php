<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class AuditLog extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = ['user_id', 'action', 'description', 'ip_address', 'user_agent'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Catat aktivitas penting. Kegagalan mencatat tidak boleh mengganggu aplikasi.
     */
    public static function record(string $action, ?string $description = null, ?int $userId = null): void
    {
        try {
            static::create([
                'user_id' => $userId ?? auth()->id(),
                'action' => $action,
                'description' => $description ? Str::limit($description, 500) : null,
                'ip_address' => request()->ip(),
                'user_agent' => Str::limit((string) request()->userAgent(), 250, ''),
            ]);
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
