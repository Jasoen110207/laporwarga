<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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

    public const FINAL_STATUSES = ['selesai', 'ditolak'];

    protected $fillable = ['ticket', 'user_id', 'category_id', 'title', 'description', 'location', 'photo', 'status'];

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

    /**
     * Pencarian aman: karakter ! % _ di-escape supaya tidak berfungsi sebagai wildcard.
     * Kolom yang dicari adalah konstanta, input hanya masuk sebagai binding.
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (blank($term)) {
            return $query;
        }

        $like = '%' . str_replace(['!', '%', '_'], ['!!', '!%', '!_'], $term) . '%';

        return $query->where(function (Builder $w) use ($like) {
            foreach (['title', 'ticket', 'location'] as $column) {
                $w->orWhereRaw("{$column} LIKE ? ESCAPE '!'", [$like]);
            }
        });
    }

    public function isFinal(): bool
    {
        return in_array($this->status, self::FINAL_STATUSES, true);
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
}
