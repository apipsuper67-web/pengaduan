<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Laporan extends Model
{
    use HasFactory;

    public const STATUSES = [
        'pending' => 'Menunggu',
        'proses' => 'Sedang diproses',
        'selesai' => 'Selesai',
    ];

    protected $fillable = ['judul', 'detail', 'tanggal', 'foto'];

    protected $attributes = ['status' => 'pending'];

    protected function casts(): array
    {
        return ['tanggal' => 'date'];
    }

    public function pelapor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pelapor_id');
    }

    public function statusDescription(): string
    {
        return match ($this->status) {
            'proses' => 'Pengaduan sedang ditindaklanjuti.',
            'selesai' => 'Pengaduan telah selesai ditangani.',
            default => 'Pengaduan menunggu peninjauan admin.',
        };
    }
}
