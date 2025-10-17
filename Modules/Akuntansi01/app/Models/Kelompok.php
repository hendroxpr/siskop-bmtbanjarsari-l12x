<?php

namespace Modules\Akuntansi01\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Akuntansi01\Database\Factories\KelompokFactory;

class Kelompok extends Model
{
    use HasFactory;

    protected $connection = 'mysql_01';
    protected $table = 'kelompok';
    protected $primaryKey = 'id';
    protected $guarded = [];
    public $timestamps = false;
    public $incrementing = false;

    public function kategori()
    {
        return $this->hasMany(Kategori::class,'idkelompok','id');
    }

    /**
     * The attributes that are mass assignable.
     */
    // protected $fillable = [];

    // protected static function newFactory(): KelompokFactory
    // {
    //     // return KelompokFactory::new();
    // }
}
