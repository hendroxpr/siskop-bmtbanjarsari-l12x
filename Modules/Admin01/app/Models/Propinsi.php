<?php

namespace Modules\Admin01\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Admin01\Database\Factories\PropinsiFactory;

class Propinsi extends Model
{
    use HasFactory;
    protected $connection = 'mysql_01';
    protected $table = 'propinsi';
    protected $primaryKey = 'id';

    protected $guarded = [];
    public $timestamps = false;
    public $incrementing = false;

    public function kabupaten()
    {
        return $this->hasMany(Kabupaten::class,'idpropinsi','id');
    }

    // protected static function newFactory(): PropinsiFactory
    // {
    //     // return PropinsiFactory::new();
    // }
}