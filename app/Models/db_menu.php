<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class db_menu extends Model
{
    use HasFactory;
    protected $table = 'db_menu';
    protected $primaryKey = 'id_menu';
    public $timestamps = false;

    protected $fillable = [
        'id_menu',
        'sub_id_menu',
        'nama_menu',
        'urutan',
        'icon_menu',
        'page',
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
        return $this->hasMany(db_user_akses::class, 'id_menu', 'id_menu');
    }
    public function subMenus()
    {
        return $this->hasMany(db_menu::class, 'sub_id_menu', 'id_menu');
    }
}
