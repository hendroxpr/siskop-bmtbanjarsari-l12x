<?php

namespace Modules\Pos01\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Pos01\Database\Factories\BayarhutangFactory;

class Bayarhutang extends Model
{
    protected $connection = 'mysql_01';
    protected $table = 'bayarhutang';
    protected $primaryKey = 'id';
    protected $guarded = [];
    public $timestamps = false;
    public $incrementing = false;    
    
    public function anggota()
    {
        return $this->belongsTo(Anggota::class,'idanggota');
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class,'idsupplier');
    }
    public function hutang()
    {
        return $this->belongsTo(Hutang::class,'idhutang');
    }
   
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $fillable = [];
}