<?php

namespace App\Models;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    protected $table = 'ms_dokter';
    protected $primaryKey = 'Id_Dokter';
    public $incrementing = false; // Karena pakai CHAR(36)
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'Id_Dokter',
        'Nama_Dokter',
        'Spesialis',
        'No_Telp',
        'Email',
        'Alamat',
        'Jadwal_Dokter',
        'Kuota_Max',
        'Create_Date',
        'Create_By',
        'Last_Update_By',
    ];

    protected $casts = [
        'Jadwal_Dokter' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->Id_Dokter = Str::uuid()->toString();
        });
    }

    public function sesi()
    {
        return $this->belongsToMany(Sesi::class, 'jadwal_dokter', 'Id_Dokter', 'Id_Sesi');
    }

    public function layanan()
    {
        return $this->belongsToMany(Layanan::class, 'layanan_dokter', 'dokter_id', 'layanan_id');
    }

    public function kunjungans()
    {
        return $this->hasMany(Kunjungan::class, 'Id_Dokter', 'Id_Dokter');
    }
    
    
}
