<?php

namespace Modules\Pos01\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Pos01\Database\Factories\RuangFactory;

class Ruang extends Model
{
    protected $connection = 'mysql_01';
    protected $table = 'ruang';
    protected $primaryKey = 'id';
    protected $guarded = [];
    public $timestamps = false;
    public $incrementing = false;
    
    public function seksi()
    {
        return $this->belongsTo(Seksi::class,'idseksi');
    }

    public function barangruang()
    {
        return $this->hasMany(Barangruang::class,'idruang','id');
    }
    public function bmasuk()
    {
        return $this->hasMany(Bmasuk::class,'idruang','id');
    }
    public function bkeluar()
    {
        return $this->hasMany(Bkeluar::class,'idruang','id');
    }
    public function mkeluar()
    {
        return $this->hasMany(Mkeluar::class,'idruang','id');
    }
    public function stok()
    {
        return $this->hasMany(Stok::class,'idruang','id');
    }
    public function stokfifo()
    {
        return $this->hasMany(Stokfifo::class,'idruang','id');
    }
    public function stoklifo()
    {
        return $this->hasMany(Stoklifo::class,'idruang','id');
    }
    public function stokmova()
    {
        return $this->hasMany(Stokmova::class,'idruang','id');
    }
    public function stokmamin()
    {
        return $this->hasMany(Stokmamin::class,'idruang','id');
    }
    public function biaya()
    {
        return $this->hasMany(Biaya::class,'idruang','id');
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $fillable = [];
}