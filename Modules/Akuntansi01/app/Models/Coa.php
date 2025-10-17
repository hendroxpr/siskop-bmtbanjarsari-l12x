<?php

namespace Modules\Akuntansi01\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Akuntansi01\Database\Factories\CoaFactory;

class Coa extends Model
{
    use HasFactory;

    protected $connection = 'mysql_01';
    protected $table = 'coa';
    protected $primaryKey = 'id';
    protected $guarded = [];
    public $timestamps = false;
    public $incrementing = false;

    public function kategori()
    {
        return $this->belongsTo(Kategori::class,'idkategori');
    }

    /**
     * The attributes that are mass assignable.
     */
    // protected $fillable = [];

    // protected static function newFactory(): CoaFactory
    // {
    //     // return CoaFactory::new();
    // }
}
