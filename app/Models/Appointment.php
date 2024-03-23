<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Appointment extends Model 
{
    use HasFactory;
    

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */  
    protected $fillable = [
        'create_user_id', 
        'customer_id', 
        'token', 
        'fullname',
        'phone',
        'email',
        'note',
        'date',
        'status',
        'isDeleted',
        'ah_id'
    ];

    public function user(): HasOne{
        return $this->hasOne(User::class, 'id', 'create_user_id');
    }

    public function customer(): HasOne{
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var string[]
     */
    protected $hidden = ['isDeleted'];
}
