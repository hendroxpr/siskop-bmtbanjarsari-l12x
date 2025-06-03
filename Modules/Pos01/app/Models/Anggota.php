<?php

namespace Modules\Pos01\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Pos01\Database\Factories\AnggotaFactory;

class Anggota extends Model
{
    use HasFactory;
    protected $connection = 'mysql_01';
    protected $table = 'anggota';
    protected $primaryKey = 'id';

    protected $guarded = [];
    public $timestamps = false;
    public $incrementing = false;

    public function lembaga()
    {
        return $this->belongsTo(lembaga::class,'idlembaga');
    }

    public function bkeluar()
    {
        return $this->hasMany(Bkeluar::class,'idanggota','id');
    }
    public function mkeluar()
    {
        return $this->hasMany(Mkeluar::class,'idanggota','id');
    }
    public function savings()
    {
        return $this->hasMany(Savings::class,'idanggota','id');
    }
    public function hutang()
    {
        return $this->hasMany(Hutang::class,'idanggota','id');
    }
    public function bayarhutang()
    {
        return $this->hasMany(Bayarhutang::class,'idanggota','id');
    }
    public function stok()
    {
        return $this->hasMany(Stok::class,'idanggota','id');
    }
    public function stokfifo()
    {
        return $this->hasMany(Stokfifo::class,'idanggota','id');
    }
    public function stokmova()
    {
        return $this->hasMany(Stokmova::class,'idanggota','id');
    }
    public function stoklifo()
    {
        return $this->hasMany(Stoklifo::class,'idanggota','id');
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
