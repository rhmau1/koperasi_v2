<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class db_user_akses extends Model
{
    use HasFactory;
    protected $table = 'db_user_akses';
    protected $primaryKey = 'id_akses';
    public $timestamps = false;

    protected $fillable = [
        'id_akses',
        'id_level',
        'id_menu',
        'hak_add',
        'hak_edit',
        'hak_delete',
        'created_at',
        'created_by',
        'modified_at',
        'modified_by',
        'deleted_at',
        'deleted_by',
    ];

    public function level()
    {
        return $this->belongsTo(db_user_level::class, 'id_level', 'id_level');
    }

    public function menu()
    {
        return $this->belongsTo(db_menu::class, 'id_menu', 'id_menu');
    }
}
