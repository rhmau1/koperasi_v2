<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class db_anggota extends Authenticatable
{
    use HasFactory;
    protected $primaryKey = 'id_anggota';
    protected $table = 'db_anggota';
    public $timestamps = false;

    protected $fillable = [
        'id_anggota',
        'nama_anggota',
        'email_anggota',
        'hp_anggota',
        'password_anggota',
        'status',
        'created_at',
        'created_by',
        'modified_at',
        'modified_by',
        'deleted_at',
        'deleted_by',
    ];
    protected $hidden = [
        'password_anggota',
    ];
    public function levelAkses()
    {
        return $this->hasOne(db_user_level_akses::class, 'id_anggota', 'id_anggota');
    }
}
