<?php

namespace Modules\Simpanan01\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Admin01\Models\Anggota;
use Modules\Akuntansi01\Models\Jenissimpanan;

// use Modules\Simpanan01\Database\Factories\NasabahFactory;

class Nasabah extends Model
{
    use HasFactory;

    protected $connection = 'mysql_01';
    protected $table = 'nasabah';
    protected $primaryKey = 'id';
    protected $guarded = [];
    public $timestamps = false;
    public $incrementing = false;

    public function jenissimpanan()
    {
        return $this->belongsTo(Jenissimpanan::class,'idjenissimpanan');
    }
    public function anggota()
    {
        return $this->belongsTo(Anggota::class,'idanggota');
    }
    
    public function nasabah()
    {
        return $this->hasMany(Nasabah::class,'idnasabah','id');
    }
    public function nasabaht()
    {
        return $this->hasMany(Nasabah::class,'idnasabaht','id');
    }

    /**
     * The attributes that are mass assignable.
     */
    // protected $fillable = [];

    // protected static function newFactory(): NasabahFactory
    // {
    //     // return NasabahFactory::new();
    // }
}
