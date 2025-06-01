<?php

namespace Modules\Pos01\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Pos01\Database\Factories\StokFactory;

class Stok extends Model
{
    protected $connection = 'mysql_01';
    protected $table = 'stok';
    protected $primaryKey = 'id';
    protected $guarded = [];
    public $timestamps = false;
    public $incrementing = false;
    
    public function barang()
    {
        return $this->belongsTo(Barang::class,'idbarang');
    }
    public function seksi()
    {
        return $this->belongsTo(Seksi::class,'idseksi');
    }
    public function ruang()
    {
        return $this->belongsTo(Ruang::class,'idruang');
    }
    public function jenispembayaran()
    {
        return $this->belongsTo(Jenispembayaran::class,'idjenispembayaran');
    }
    public function anggota()
    {
        return $this->belongsTo(Anggota::class,'idanggota');
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class,'idsupplier');
    }
   
   
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $fillable = [];
}