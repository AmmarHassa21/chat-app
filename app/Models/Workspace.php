<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Workspace extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'workspaces';

    protected $fillable = [
        'name',
        'description',
        'owner_id',
        'member_ids', // MongoDB mein hum IDs ka array rakhte hain
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    // Teams relationship
    public function teams()
    {
        return $this->hasMany(Team::class);
    }
}