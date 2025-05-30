<?php

namespace Modules\Pos01\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Pos01\Database\Factories\StokFactory;

class Stokmamin extends Model
{
    protected $connection = 'mysql_01';
    protected $table = 'stokmamin';
    protected $primaryKey = 'id';
    protected $guarded = [];
    public $timestamps = false;
    public $incrementing = false;
    
    public function mamin()
    {
        return $this->belongsTo(Mamin::class,'idmamin');
    }
    public function anggota()
    {
        return $this->belongsTo(Anggota::class,'idanggota');
    }
    public function jenispembayaran()
    {
        return $this->belongsTo(Jenispembayaran::class,'idjenispembayaran');
    }
   
   
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $fillable = [];
}