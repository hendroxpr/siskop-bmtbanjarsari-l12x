<?php

namespace Modules\Simpanan01\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Simpanan01\Database\Factories\TfkeluarFactory;

class Tfmasuk extends Model
{
    use HasFactory;

    protected $connection = 'mysql_01';
    protected $table = 'tfmasuk';
    protected $primaryKey = 'id';
    protected $guarded = [];
    public $timestamps = false;
    public $incrementing = false;

    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class,'idnasabah');
    }
    public function nasabahs()
    {
        return $this->belongsTo(Nasabah::class,'idnasabahs');
    }
    public function tfkeluar()
    {
        return $this->belongsTo(Tfkeluar::class,'idtfkeluar');
    }

    /**
     * The attributes that are mass assignable.
     */
    // protected $fillable = [];

    // protected static function newFactory(): TfkeluarFactory
    // {
    //     // return TfkeluarFactory::new();
    // }
}
