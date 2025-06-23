<?php

namespace Modules\Pos01\Models;

use App\Models\Users;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Pos01\Database\Factories\BiayaFactory;

class Biaya extends Model
{
    protected $connection = 'mysql_01';
    protected $table = 'biaya';
    protected $primaryKey = 'id';
    protected $guarded = [];
    public $timestamps = false;
    public $incrementing = false;
    
    public function jenisbiaya()
    {
        return $this->belongsTo(Jenisbiaya::class,'idjenisbiaya');
    }
    public function kategoribiaya()
    {
        return $this->belongsTo(Kategoribiaya::class,'idkategoribiaya');
    }
    public function satuan()
    {
        return $this->belongsTo(Satuan::class,'idsatuan');
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class,'idsupplier');
    }
    public function ruang()
    {
        return $this->belongsTo(Ruang::class,'idruang');
    }
    
    public function users()
    {
        return $this->belongsTo(Users::class,'iduser');
    }
   
   
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $fillable = [];
}