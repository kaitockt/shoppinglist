<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingListUser extends Model
{
    use HasFactory;

    protected $table ="shoppinglist_user";

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function shoppingList(){
        return $this->belongsTo(ShoppingList::class);
    }
}
