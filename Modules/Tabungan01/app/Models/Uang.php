<?php

namespace Modules\Tabungan01\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Tabungan01\Database\Factories\UangFactory;

class Uang extends Model
{
    use HasFactory;

    protected $connection = 'mysql_01';
    protected $table = 'uang';
    protected $primaryKey = 'id';
    protected $guarded = [];
    public $timestamps = false;
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     */
    // protected $fillable = [];

    // protected static function newFactory(): UangFactory
    // {
    //     // return UangFactory::new();
    // }
}
