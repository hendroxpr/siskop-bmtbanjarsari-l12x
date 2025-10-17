<?php

namespace Modules\Akuntansi01\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Akuntansi01\Database\Factories\KategoriFactory;

class Kategori extends Model
{
    use HasFactory;

    protected $connection = 'mysql_01';
    protected $table = 'kategori';
    protected $primaryKey = 'id';
    protected $guarded = [];
    public $timestamps = false;
    public $incrementing = false;

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class,'idkelompok');
    }

    public function coa()
    {
        return $this->hasMany(Coa::class,'idkategori','id');
    }

    /**
     * The attributes that are mass assignable.
     */
    // protected $fillable = [];

    // protected static function newFactory(): KategoriFactory
    // {
    //     // return KategoriFactory::new();
    // }
}
