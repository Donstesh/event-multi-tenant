<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


    class Organization extends Model
    {
        protected $fillable = ['name', 'slug'];

        public function users()
        {
            return $this->hasMany(User::class);
        }

        public function events()
        {
            return $this->hasMany(Event::class);
        }
    }

