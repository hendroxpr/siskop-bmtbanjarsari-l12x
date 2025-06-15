<?php

namespace Modules\Pos01\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Pos01\Database\Factories\SatuanFactory;

class Satuan extends Model
{
    use HasFactory;
    protected $connection = 'mysql_01';
    protected $table = 'satuan';
    protected $primaryKey = 'id';

    protected $guarded = [];
    public $timestamps = false;
    public $incrementing = false;

    public function barang()
    {
        return $this->hasMany(Barang::class,'idsatuan','id');
    }
    public function biaya()
    {
        return $this->hasMany(Biaya::class,'idsatuan','id');
    }
    public function pendapatan()
    {
        return $this->hasMany(Biaya::class,'idsatuan','id');
    }
    /**
     * The attributes that are mass assignable.
     */
    // protected $fillable = [];

    // protected static function newFactory(): AnggotaFactory
    // {
    //     // return AnggotaFactory::new();
    // }
}
