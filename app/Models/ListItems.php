<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use App\Models\Favourites;

class ListItems extends Model
{
    use HasFactory;

    protected $table = "listitems";

    protected $fillable = ["list_id", "name", "priority", "done", "buy_by", "repeat"];

    public function list(){
        return $this->belongsTo(ShoppingList::class, 'list_id');
    }

    public function fav(){
        $uid = Auth::id();
        return Favourites::where([
                ["user_id", $uid],
                ["name", $this->name]
            ])->exists();
    }
}
