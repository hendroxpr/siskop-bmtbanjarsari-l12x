<?php

namespace Modules\Akuntansi01\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Akuntansi01\Database\Factories\JenisjurnalFactory;

class Jenisjurnal extends Model
{
    use HasFactory;

    protected $connection = 'mysql_01';
    protected $table = 'jenisjurnal';
    protected $primaryKey = 'id';
    protected $guarded = [];
    public $timestamps = false;
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     */
    // protected $fillable = [];

    // protected static function newFactory(): JenisjurnalFactory
    // {
    //     // return JenisjurnalFactory::new();
    // }
}
