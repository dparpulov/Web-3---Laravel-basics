<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    //We can change the names
    //Table name
    protected $table = 'posts';

    //Primary key
    protected $primaryKey = 'id';

    //Timestamps
    public $timestamps = true; //true is default we can put false

    public function user(){
        return $this->belongsTo('App\User');
    }
}
