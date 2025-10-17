<?php

namespace Modules\Admin01\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Pos01\Database\Factories\KecamatanFactory;

class Kecamatan extends Model
{
    protected $connection = 'mysql_01';
    protected $table = 'kecamatan';
    protected $primaryKey = 'id';
    protected $guarded = [];
    public $timestamps = false;
    public $incrementing = false;
    
    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class,'idkabupaten');
    }

    public function desa()
    {
        return $this->hasMany(Desa::class,'idkecamatan','id');
    }
   
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $fillable = [];
}