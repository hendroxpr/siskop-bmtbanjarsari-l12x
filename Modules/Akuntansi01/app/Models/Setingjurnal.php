<?php

namespace Modules\Akuntansi01\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// use Modules\Akuntansi01\Database\Factories\SetingjurnalFactory;

class Setingjurnal extends Model
{
    use HasFactory;

    protected $connection = 'mysql_01';
    protected $table = 'setingjurnal';
    protected $primaryKey = 'id';
    protected $guarded = [];
    public $timestamps = false;
    public $incrementing = false;

    public function coa01d()
    {
        return $this->belongsTo(Coa::class,'idcoa01d');
    }
    public function coa01k()
    {
        return $this->belongsTo(Coa::class,'idcoa01k');
    }
    public function coa02d()
    {
        return $this->belongsTo(Coa::class,'idcoa02d');
    }
    public function coa02k()
    {
        return $this->belongsTo(Coa::class,'idcoa02k');
    }
    public function coa03d()
    {
        return $this->belongsTo(Coa::class,'idcoa03d');
    }
    public function coa03k()
    {
        return $this->belongsTo(Coa::class,'idcoa03k');
    }
    public function coa04d()
    {
        return $this->belongsTo(Coa::class,'idcoa04d');
    }
    public function coa04k()
    {
        return $this->belongsTo(Coa::class,'idcoa04k');
    }
    public function coa05d()
    {
        return $this->belongsTo(Coa::class,'idcoa05d');
    }
    public function coa05k()
    {
        return $this->belongsTo(Coa::class,'idcoa05k');
    }
    public function coa06d()
    {
        return $this->belongsTo(Coa::class,'idcoa06d');
    }
    public function coa06k()
    {
        return $this->belongsTo(Coa::class,'idcoa06k');
    }

    public function jenisjurnal01d()
    {
        return $this->belongsTo(Jenisjurnal::class,'idjenisjurnal01d');
    }
    public function jenisjurnal01k()
    {
        return $this->belongsTo(Jenisjurnal::class,'idjenisjurnal01k');
    }
    public function jenisjurnal02d()
    {
        return $this->belongsTo(Jenisjurnal::class,'idjenisjurnal02d');
    }
    public function jenisjurnal02k()
    {
        return $this->belongsTo(Jenisjurnal::class,'idjenisjurnal02k');
    }
    public function jenisjurnal03d()
    {
        return $this->belongsTo(Jenisjurnal::class,'idjenisjurnal03d');
    }
    public function jenisjurnal03k()
    {
        return $this->belongsTo(Jenisjurnal::class,'idjenisjurnal03k');
    }
    public function jenisjurnal04d()
    {
        return $this->belongsTo(Jenisjurnal::class,'idjenisjurnal04d');
    }
    public function jenisjurnal04k()
    {
        return $this->belongsTo(Jenisjurnal::class,'idjenisjurnal04k');
    }
    public function jenisjurnal05d()
    {
        return $this->belongsTo(Jenisjurnal::class,'idjenisjurnal05d');
    }
    public function jenisjurnal05k()
    {
        return $this->belongsTo(Jenisjurnal::class,'idjenisjurnal05k');
    }
    public function jenisjurnal06d()
    {
        return $this->belongsTo(Jenisjurnal::class,'idjenisjurnal06d');
    }
    public function jenisjurnal06k()
    {
        return $this->belongsTo(Jenisjurnal::class,'idjenisjurnal06k');
    }

    /**
     * The attributes that are mass assignable.
     */
    // protected $fillable = [];

    // protected static function newFactory(): ProduktabunganFactory
    // {
    //     // return ProduktabunganFactory::new();
    // }
}
