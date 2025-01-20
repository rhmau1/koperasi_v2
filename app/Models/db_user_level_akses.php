<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class db_user_level_akses extends Model
{
    use HasFactory;
    protected $table = 'db_user_level_akses';
    protected $primaryKey = 'id_user_level_akses';
    public $timestamps = false;

    protected $fillable = [
        'id_user_level_akses',
        'jenis_user',
        'id_user',
        'id_anggota',
        'id_pegawai',
        'id_level',
        'status',
        'created_at',
        'created_by',
        'modified_at',
        'modified_by',
        'deleted_at',
        'deleted_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function pegawai()
    {
        return $this->belongsTo(db_pegawai::class, 'id_pegawai', 'id_pegawai');
    }

    public function level()
    {
        return $this->belongsTo(db_user_level::class, 'id_level', 'id_level');
    }
}
