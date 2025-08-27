<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Ruangan extends Model
{
    protected $table = 'ms_ruangan';
    protected $primaryKey = 'Id_Ruangan';
    public $incrementing = false; // UUID bukan auto-increment
    protected $keyType = 'string'; // UUID adalah string

    protected $fillable = [
        'Id_Ruangan',
        'Nama_Ruangan',
        'Jenis_Ruangan',
        'Lantai',
        'Status',
        'Keterangan',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function kunjungans()
    {
        return $this->hasMany(Kunjungan::class, 'Id_Ruangan', 'Id_Ruangan');
    }

    public function dokters()
    {
        return $this->hasMany(Dokter::class, 'Spesialis', 'deskripsi');
    }
    
}
