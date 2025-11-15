<?php

namespace Modules\Akuntansi01\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Admin01\Models\Anggota;
use Modules\Simpanan01\Models\Nasabah;
use Modules\Simpanan01\Models\Sandi;


// use Modules\Akuntansi01\Database\Factories\JurnalumumFactory;

class Jurnalumum extends Model
{
    use HasFactory;

    protected $connection = 'mysql_01';
    protected $table = 'jurnalumum';
    protected $primaryKey = 'id';
    protected $guarded = [];
    public $timestamps = false;
    public $incrementing = false;

    public function coa()
    {
        return $this->belongsTo(Coa::class,'idcoa');
    }
    public function sandi()
    {
        return $this->belongsTo(Sandi::class,'idsandi');
    }
    public function jenisjurnal()
    {
        return $this->belongsTo(Jenisjurnal::class,'idjenisjurnal');
    }
    public function anggota()
    {
        return $this->belongsTo(Anggota::class,'idsumber');
    }
    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class,'idsumber');
    }
    public function anggotat()
    {
        return $this->belongsTo(Anggota::class,'idtarget');
    }
    public function nasabaht()
    {
        return $this->belongsTo(Nasabah::class,'idtarget');
    }

    /**
     * The attributes that are mass assignable.
     */
    // protected $fillable = [];

    // protected static function newFactory(): JurnalumumFactory
    // {
    //     // return JurnalumumFactory::new();
    // }
}
