<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prophecy\Doubler\Generator\Node\ReturnTypeNode;

class Wishlist extends Model
{
    protected $fillable = ['course_id', 'user_id'];

    protected static function boot(){
        parent::boot();
        if (!app()->runningInConsole()) {
            self::creating(function($table){
                $table->user_id = auth()->id();
            });
        }
    }

    public function newQuery()
    {
        //con esto siempre seleccionará el usuario conectado y sus datos. Así sobreescribimos la consulta base de eloquent
        if (auth()->check()) {
            return parent::newQuery()
                ->where('user_id', auth()->id());
        }
        return parent::newQuery();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
