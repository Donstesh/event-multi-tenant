<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

// class Event extends Model
// {
//     use SoftDeletes, LogsActivity;

//     protected static $logAttributes = ['title', 'description', 'date', 'price'];
// }

class Event extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'title', 'description', 'venue', 'date', 'price', 'max_attendees', 'organization_id'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function attendees()
    {
        return $this->hasMany(Attendee::class);
    }
}
