<?php

namespace Modules\Akuntansi01\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Admin01\Models\Propinsi;
use Modules\Tabungan01\Models\Nasabah;

// use Modules\Akuntansi01\Database\Factories\ProdukFactory;

class Produk extends Model
{
    use HasFactory;

    protected $connection = 'mysql_01';
    protected $table = 'produk';
    protected $primaryKey = 'id';
    protected $guarded = [];
    public $timestamps = false;
    public $incrementing = false;

public function nasabah()
    {
        return $this->hasMany(Nasabah::class,'idproduk','id');
    }

    public function coasetord()
    {
        return $this->belongsTo(Coa::class,'idcoasetord');
    }
    public function coasetork()
    {
        return $this->belongsTo(Coa::class,'idcoasetork');
    }
    
    public function coatarikd()
    {
        return $this->belongsTo(Coa::class,'idcoatarikd');
    }
    public function coatarikk()
    {
        return $this->belongsTo(Coa::class,'idcoatarikk');
    }

    public function coatfmasukd()
    {
        return $this->belongsTo(Coa::class,'idcoatfmasukd');
    }
    public function coatfmasukk()
    {
        return $this->belongsTo(Coa::class,'idcoatfmasukk');
    }

    public function coatfkeluard()
    {
        return $this->belongsTo(Coa::class,'idcoatfkeluard');
    }
    public function coatfkeluark()
    {
        return $this->belongsTo(Coa::class,'idcoatfkeluark');
    }

    public function jenisjurnalsetord()
    {
        return $this->belongsTo(Jenisjurnal::class,'idjenisjurnalsetord');
    }
    public function jenisjurnalsetork()
    {
        return $this->belongsTo(Jenisjurnal::class,'idjenisjurnalsetork');
    }
    public function jenisjurnaltarikd()
    {
        return $this->belongsTo(Jenisjurnal::class,'idjenisjurnaltarikd');
    }
    public function jenisjurnaltarikk()
    {
        return $this->belongsTo(Jenisjurnal::class,'idjenisjurnaltarikk');
    }

    public function jenisjurnaltfmasukd()
    {
        return $this->belongsTo(Jenisjurnal::class,'idjenisjurnaltfmasukd');
    }
    public function jenisjurnaltfmasukk()
    {
        return $this->belongsTo(Jenisjurnal::class,'idjenisjurnaltfmasukk');
    }

    public function jenisjurnaltfkeluard()
    {
        return $this->belongsTo(Jenisjurnal::class,'idjenisjurnaltfkeluard');
    }
    public function jenisjurnaltfkeluark()
    {
        return $this->belongsTo(Jenisjurnal::class,'idjenisjurnaltfkeluark');
    }
    public function produk()
    {
        return $this->hasMany(Nasabah::class,'idproduk','id');
    }


    /**
     * The attributes that are mass assignable.
     */
    // protected $fillable = [];

    // protected static function newFactory(): ProdukFactory
    // {
    //     // return ProdukFactory::new();
    // }
}
