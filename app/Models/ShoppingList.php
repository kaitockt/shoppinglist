<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

class Shoppinglist extends Model
{
    use HasFactory;

    protected $table ='shoppinglist';

    protected $primaryKey = 'id';

    protected $fillable = ['name', 'created_by'];

    public function list(){
        return $this->select(
            'SELECT l.*, sum(if(i.valid_from <= NOW(), 1, 0)) AS validItemsCount
            FROM shoppinglist AS l INNER JOIN shoppinglist_user AS su ON l.id = su.shoppinglist_id
            LEFT JOIN listitems AS i ON l.id = i.list_id
            WHERE su.user_id = '.Auth::id().' AND su.status = 1
            GROUP BY su.id ORDER BY su.last_opened DESC;');
    }

    public function users(){
        return $this->belongsToMany(User::class, 'shoppinglist_user')
            ->withPivot(['status', 'last_opened', 'inviter_id'])
            ->orderBy('last_opened', 'desc');
    }


    public function items(){
        return $this->hasMany(ListItems::class, 'list_id');
    }

    public function creator(){
        return $this->belongsTo(User::class, 'created_by');
    }
}
