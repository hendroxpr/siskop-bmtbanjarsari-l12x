<?php

namespace Modules\Pinjaman01\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Admin01\Models\Anggota;
use Modules\Akuntansi01\Models\Coa;
use Modules\Akuntansi01\Models\Jenisjurnal;
use Modules\Akuntansi01\Models\Jenispinjaman;
use Modules\Simpanan01\Models\Sandi;

// use Modules\Pinjaman01\Database\Factories\JurnalpinjamanFactory;

class Jurnalpinjaman extends Model
{
    use HasFactory;
    protected $connection = 'mysql_01';
    protected $table = 'jurnalpinjaman';
    protected $primaryKey = 'id';
    protected $guarded = [];
    public $timestamps = false;
    public $incrementing = false;

    public function coa()
    {
        return $this->belongsTo(Coa::class,'idcoa');
    }
    public function anggota()
    {
        return $this->belongsTo(Anggota::class,'idanggota');
    }
    public function jenispinjaman()
    {
        return $this->belongsTo(Jenispinjaman::class,'idjenispinjaman');
    }
    public function jenisjurnal()
    {
        return $this->belongsTo(Jenisjurnal::class,'idjenisjurnal');
    }
    public function sandi()
    {
        return $this->belongsTo(Sandi::class,'idsandi');
    }
    /**
     * The attributes that are mass assignable.
     */
    // protected $fillable = [];

    // protected static function newFactory(): JurnalpinjamanFactory
    // {
    //     // return JurnalpinjamanFactory::new();
    // }
}
