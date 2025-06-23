<?php

namespace Modules\Pos01\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Pos01\Database\Factories\KategoriFactory;

class Kategoribiaya extends Model
{
    protected $connection = 'mysql_01';
    protected $table = 'kategoribiaya';
    protected $primaryKey = 'id';
    protected $guarded = [];
    public $timestamps = false;
    public $incrementing = false;
    
    public function biaya()
    {
        return $this->hasMany(Biaya::class,'idkategoribiaya','id');
    }
    
    
   
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $fillable = [];
}