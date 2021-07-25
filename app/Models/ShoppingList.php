<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shoppinglist extends Model
{
    use HasFactory;

    protected $table ='shoppinglist';

    protected $primaryKey = 'id';

    protected $fillable = ['name', 'created_by'];

    public function users(){
        return $this->belongsToMany(User::class);
    }

    public function items(){
        return $this->hasMany(ListItems::class, 'list_id');
    }

    public function creator(){
        return $this->belongsTo(User::class, 'created_by');
    }
}
