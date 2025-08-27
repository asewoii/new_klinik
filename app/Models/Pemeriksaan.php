<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Pemeriksaan extends Model
{
    protected $table = 'tr_pemeriksaan';
    protected $primaryKey = 'Id_Pemeriksaan';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false; // <== ini penting

    protected $fillable = [
        'Id_Pemeriksaan', 'Id_Kunjungan', 'Id_Dokter', 'Diagnosa', 'Tindakan',
        'Resep', 'Catatan', 'Tanggal_Pemeriksaan', 'Jam_Pemeriksaan',
        'Create_Date', 'Create_By'
    ];

     protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->Id_Pemeriksaan)) {
                $model->Id_Pemeriksaan = (string) Str::uuid();
            }
        });
    }

    // relasi opsional
    public function kunjungan() {
        return $this->belongsTo(Kunjungan::class, 'Id_Kunjungan','Id_Kunjungan');
    }

    public function dokter() {
        return $this->belongsTo(Dokter::class, 'Id_Dokter','Id_Dokter');
    }

    public function pasien() {
        return $this->belongsTo(Pasien::class, 'Id_Pasien','Id_Pasien');
    }

    public function indikasi()
{
    return $this->belongsTo(Indikasi::class, 'Kode_Indikasi', 'Kode_Indikasi');
}
}
