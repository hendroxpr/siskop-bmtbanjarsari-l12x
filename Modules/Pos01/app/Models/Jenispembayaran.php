<?php

namespace Modules\Pos01\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Pos01\Database\Factories\JenispembayaranFactory;

class Jenispembayaran extends Model
{
    use HasFactory;
    protected $connection = 'mysql_01';
    protected $table = 'jenispembayaran';
    protected $primaryKey = 'id';

    protected $guarded = [];
    public $timestamps = false;
    public $incrementing = false;

    public function stok()
    {
        return $this->hasMany(Stok::class,'idjenispembayaran','id');
    }
    public function stokfifo()
    {
        return $this->hasMany(Stokfifo::class,'idjenispembayaran','id');
    }
    public function stoklifo()
    {
        return $this->hasMany(Stoklifo::class,'idjenispembayaran','id');
    }
    public function stokmova()
    {
        return $this->hasMany(Stokmova::class,'idjenispembayaran','id');
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
