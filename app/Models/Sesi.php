<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sesi extends Model
{
    protected $table = 'ms_sesi';
    protected $primaryKey = 'Id_Sesi';
    protected $keyType = 'string';   
    public $timestamps = false; 
    protected $fillable = [
        'Id_Sesi',
        'Nama_Sesi',
        'Mulai_Sesi',
        'Selesai_Sesi',
        'Kuota_Max',
        'Kuota_Terpakai',
        'Kuota_Sisa',
        'Status',
        'Create_Date',
        'Create_By',
        'Last_Update',
        'Last_Update_By'
    ];

    

    public function dokter() {
        return $this->belongsToMany(Dokter::class, 'jadwal_dokter', 'Id_Sesi', 'Id_Dokter');
    }

    public function kunjungan() {
        return $this->hasMany(Kunjungan::class, 'Id_Sesi', 'Id_Sesi');
    }

    // accessor agar lebih mudah mengambil nama table
    public function getNamaDokterAttribute() {
        return $this->Dokter;
    }
    



}