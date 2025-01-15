<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class db_pegawai extends Authenticatable
{
    use HasFactory;
    protected $primaryKey = 'id_pegawai';
    protected $table = 'db_pegawai';
    public $timestamps = false;

    protected $fillable = [
        'id_pegawai',
        'nama_pegawai',
        'email_pegawai',
        'hp_pegawai',
        'password_pegawai',
        'status',
        'created_at',
        'created_by',
        'modified_at',
        'modified_by',
        'deleted_at',
        'deleted_by',
    ];
    protected $hidden = [
        'password_pegawai',
    ];
    public function levelAkses()
    {
        return $this->hasOne(db_user_level_akses::class, 'id_pegawai', 'id_pegawai');
    }
}
