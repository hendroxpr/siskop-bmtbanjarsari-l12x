<?php

namespace Modules\Simpanan01\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Akuntansi01\Models\Coa;
use Modules\Akuntansi01\Models\Jenisjurnal;
use Modules\Akuntansi01\Models\Jenissimpanan;
use Modules\Simpanan01\Models\Nasabah;
use Modules\Simpanan01\Models\Sandi;

// use Modules\Simpanan01\Database\Factories\JurnalsimpananFactory;

class Jurnalsimpanan extends Model
{
    use HasFactory;
    protected $connection = 'mysql_01';
    protected $table = 'jurnalsimpanan';
    protected $primaryKey = 'id';
    protected $guarded = [];
    public $timestamps = false;
    public $incrementing = false;

    public function coa()
    {
        return $this->belongsTo(Coa::class,'idcoa');
    }
    public function jenissimpanan()
    {
        return $this->belongsTo(Jenissimpanan::class,'idjenissimpanan');
    }
    public function jenisjurnal()
    {
        return $this->belongsTo(Jenisjurnal::class,'idjenisjurnal');
    }
    public function sandi()
    {
        return $this->belongsTo(Sandi::class,'idsandi');
    }
    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class,'idnasabah');
    }
    public function target()
    {
        return $this->belongsTo(Nasabah::class,'idtarget');
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
