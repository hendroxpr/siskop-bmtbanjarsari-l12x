<?php

namespace Modules\Simpanan01\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Simpanan01\Database\Factories\TsetorFactory;

class Tsetor extends Model
{
    use HasFactory;

    protected $connection = 'mysql_01';
    protected $table = 'tsetor';
    protected $primaryKey = 'id';
    protected $guarded = [];
    public $timestamps = false;
    public $incrementing = false;

    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class,'idnasabah');
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
