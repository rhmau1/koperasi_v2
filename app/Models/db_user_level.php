<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class db_user_level extends Model
{
    use HasFactory;
    protected $table = 'db_user_level';
    protected $primaryKey = 'id_level';
    public $timestamps = false;

    protected $fillable = [
        'id_level',
        'nama_level',
        'status',
        'created_at',
        'created_by',
        'modified_at',
        'modified_by',
        'deleted_at',
        'deleted_by',
    ];
    public function userAkses()
    {
        return $this->hasMany(db_user_akses::class, 'id_level', 'id_level');
    }
}
