<?php

namespace Modules\Pos01\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Pos01\Database\Factories\MaminFactory;

class Mamin extends Model
{
    protected $connection = 'mysql_01';
    protected $table = 'mamin';
    protected $primaryKey = 'id';
    protected $guarded = [];
    public $timestamps = false;
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $fillable = [];
    public function satuan()
    {
        return $this->belongsTo(Satuan::class,'idsatuan');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class,'idkategori');
    }

    public function mkeluar()
    {
        return $this->hasMany(Mkeluar::class,'idanggota','id');
    }
   


}