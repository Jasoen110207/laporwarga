<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Report extends Model
{
    public const STATUSES = [
        'baru' => 'Baru masuk',
        'diproses' => 'Sedang diproses',
        'selesai' => 'Selesai',
        'ditolak' => 'Ditolak',
    ];

    protected $fillable = ['ticket', 'user_id', 'category_id', 'other_category', 'title', 'description', 'location', 'photo', 'status'];

    protected static function booted(): void
    {
        static::creating(function (Report $report) {
            $report->ticket ??= self::newTicket();
        });
    }

    public static function newTicket(): string
    {
        do {
            $ticket = 'LW-' . strtoupper(Str::random(6));
        } while (self::where('ticket', $ticket)->exists());

        return $ticket;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function responses(): HasMany
    {
        return $this->hasMany(ReportResponse::class)->oldest();
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getCategoryLabelAttribute(): string
    {
        if ($this->category?->slug === 'lainnya' && $this->other_category) {
            return 'Lainnya: ' . $this->other_category;
        }

        return $this->category?->name ?? '-';
    }
}