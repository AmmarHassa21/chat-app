<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model; 

class Channel extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'channels';

    protected $fillable = [
        'team_id',
        'created_by',
        'name',
        'type', // public, private, direct
        'description',
        'member_ids',
    ];

    // Relationships
    public function messages() { return $this->hasMany(Message::class); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
}