<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// MongoDB model extend karein
use MongoDB\Laravel\Eloquent\Model; 

class Message extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'messages';

    protected $fillable = [
        'channel_id',
        'user_id',
        'body',
    ];

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}