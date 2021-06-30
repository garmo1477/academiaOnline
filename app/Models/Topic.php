<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Topic extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $perPage = 10;

    const PENDING = 'PENDING';

    const SOLVED ='SOLVED';

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        //un debate puede tener varias respuestas
        return $this->hasMany(Reply::class);
    }
}
