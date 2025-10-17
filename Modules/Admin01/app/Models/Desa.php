<?php

namespace Modules\Admin01\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Pos01\Database\Factories\DesaFactory;

class Desa extends Model
{
    protected $connection = 'mysql_01';
    protected $table = 'desa';
    protected $primaryKey = 'id';
    protected $guarded = [];
    public $timestamps = false;
    public $incrementing = false;
    
    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class,'idkecamatan');
    }

    public function anggota()
    {
        return $this->hasMany(Anggota::class,'iddesa','id');
    }
   
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $fillable = [];
}