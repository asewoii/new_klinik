<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    protected $primaryKey = 'Id_Pasien';
    protected $table = 'ms_pasien';
    public $timestamps = false;
    protected $keyType ='string';

    protected $fillable = [
        'Id_Pasien',
        'Qr_Url',
        'Nik',
        'Nama_Pasien',
        'Tanggal_Lahir',
        'Umur',
        'Jk',
        'Alamat',
        'No_Tlp',
        'Pin',
        'Jadwal_Kedatangan',
        'Tanggal_Registrasi',
        'Dibuat_Oleh',
        'Create_Date',
        'Create_By',
        'Last_Update',
        'Last_Update_By'
    ];

    public function sesi(){
        return $this-> belongsTo(Sesi::class,'Id_Sesi', 'Id_Sesi');
    }

    public function layanan(){
        return $this-> belongsTo(Layanan::class,'Id_Layanan', 'Id_Layanan');
    }
    public function kunjungan(){
        return $this->hasMany(Kunjungan::class, 'Id_Pasien', 'Id_Pasien');
    }

    public function pemeriksaan(){
    return $this->hasOne(Pemeriksaan::class, 'Id_Kunjungan');
}
}
