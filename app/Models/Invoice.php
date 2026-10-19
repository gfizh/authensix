<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoice';
    protected $primaryKey = 'id_invoice';
    public $timestamps = false;

    protected $fillable = [
        'id_pesanan',
        'nomor_invoice',
        'url_pdf_invoice',
        'tanggal_terbit',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_terbit' => 'datetime',
        ];
    }

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }
}
