<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class db_user_level_akses extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_user_level_akses';
    public $timestamps = false;

    protected $fillable = [
        'id_user_level_akses',
        'jenis_user',
        'id_user',
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
}
