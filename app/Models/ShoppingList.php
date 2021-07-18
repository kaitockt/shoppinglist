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

    public function user(){
        return $this->belongsToMany(User::class);
    }

    public function items(){
        return $this->hasMany(ListItem::class);
    }
}
