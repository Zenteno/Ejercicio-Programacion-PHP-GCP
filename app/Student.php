<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rut', 'name','lastName','age','course'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'created_at','updated_at'
    ];

    public function course(){
        return $this->belongsTo("App\Course","course","id");
    }

}
