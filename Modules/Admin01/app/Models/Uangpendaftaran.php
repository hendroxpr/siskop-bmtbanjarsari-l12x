<?php

namespace Modules\Admin01\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Admin01\Database\Factories\UangpendaftaranFactory;

class Uangpendaftaran extends Model
{
    use HasFactory;

    protected $connection = 'mysql_01';
    protected $table = 'uangpendaftaran';
    protected $primaryKey = 'id';
    protected $guarded = [];
    public $timestamps = false;
    public $incrementing = false;

    public function anggota()
    {
        return $this->belongsTo(Anggota::class,'idanggota');
    }

    /**
     * The attributes that are mass assignable.
     */
    // protected $fillable = [];

    // protected static function newFactory(): TsetorFactory
    // {
    //     // return TsetorFactory::new();
    // }
}
