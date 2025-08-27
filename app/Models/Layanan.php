<?php
namespace App\Models;
use App\Models\Kunjungan;
use App\Models\Dokter;

use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{

    protected $table = 'ms_layanan'; 

    protected $primaryKey = 'Id_Layanan';// sesuaikan

    public $incrementing = false;// sesuaikan jika bukan auto increment

    protected $keyType = 'string';// sesuaikan jika tipe string

    public $timestamps = false;

    protected $fillable = [
        'Id_Layanan',
        'Nama_Layanan',
        'Create_Date',
        'Create_By'
    ];

    // Mengambil data dokter adri kunjungan -> sesi = Nama Dokter ( KD = Kunjngan Dokter )
    public function kunjungans() {
        return $this->hasMany(Kunjungan::class, 'Id_Layanan', 'Id_Layanan');
    }

    public function dokters()
    {
        return $this->hasMany(Dokter::class, 'Spesialis', 'Nama_Layanan');
    }
    
}
