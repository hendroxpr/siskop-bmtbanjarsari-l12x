<?php

namespace Modules\Admin01\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Pos01\Database\Factories\KabupatenFactory;

class Kabupaten extends Model
{
    protected $connection = 'mysql_01';
    protected $table = 'kabupaten';
    protected $primaryKey = 'id';
    protected $guarded = [];
    public $timestamps = false;
    public $incrementing = false;
    
    public function propinsi()
    {
        return $this->belongsTo(Propinsi::class,'idpropinsi');
    }

    public function kecamatan()
    {
        return $this->hasMany(Kecamatan::class,'idkabupaten','id');
    }
   
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $fillable = [];
}