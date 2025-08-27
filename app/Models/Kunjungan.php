<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kunjungan extends Model
{
    protected $table = 'tr_kunjungan';
    public $timestamps = false; // <--- ini baris penting
    protected $primaryKey = 'Id_Kunjungan';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'Id_Kunjungan',
        'Id_Dokter',
        'Jadwal',
        'Id_Ruangan',
        'QRK',
        'Id_Pasien',
        'Id_Layanan',
        'Keluhan',
        'Nik',
        'Nama_Pasien',
        'Tanggal_Registrasi',
        'Jadwal_Kedatangan',
        'Nomor_Urut',
        'Status',
        'Create_Date',
        'Create_By',
    ];

    public function pasien(){
        return $this->belongsTo(Pasien::class, 'Id_Pasien', 'Id_Pasien');
    }

    public function layanan(){
    return $this->belongsTo(Layanan::class, 'Id_Layanan', 'Id_Layanan');
    }

    public function dokter(){
        return $this->belongsTo(Dokter::class, 'Id_Dokter', 'Id_Dokter');
    }

    public function pemeriksaan(){
        return $this->hasOne(Pemeriksaan::class, 'Id_Kunjungan');
    }

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'Id_Ruangan', 'Id_Ruangan');
    }


} 
