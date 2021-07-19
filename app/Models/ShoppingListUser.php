<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingListUser extends Model
{
    use HasFactory;

    protected $table ="shoppinglist_user";

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function shoppingList(){
        return $this->belongsTo(ShoppingList::class, 'shoppinglist_id');
    }

    public function inviter(){
        return $this->belongsTo(User::class, 'inviter');
    }
}
