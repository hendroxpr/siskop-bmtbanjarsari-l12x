<?php

namespace Modules\Admin01\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Tabungan01\Models\Nasabah;

// use Modules\Pos01\Database\Factories\AnggotaFactory;

class Anggota extends Model
{
    protected $connection = 'mysql_01';
    protected $table = 'anggota';
    protected $primaryKey = 'id';
    protected $guarded = [];
    public $timestamps = false;
    public $incrementing = false;
    
    public function desa()
    {
        return $this->belongsTo(Desa::class,'iddesa');
    }

    public function nasabah()
    {
        return $this->hasMany(Nasabah::class,'idanggota','id');
    }
   
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $fillable = [];
}