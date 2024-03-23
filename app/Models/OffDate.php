<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OffDate extends Model 
{
    use HasFactory;
    
    protected $table = 'offdates';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */  
    protected $fillable = [
        'user_id', 'note', 'startdate', 'enddate',
    ];

    public function user(): HasOne{
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var string[]
     */
    protected $hidden = [];
}
