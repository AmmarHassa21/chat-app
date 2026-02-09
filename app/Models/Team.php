<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model; 
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Team extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'teams';

    protected $fillable = [
        'workspace_id',
        'owner_id',
        'name',
        'description',
        'member_ids' // Array of strings
    ];

    public function workspace() { return $this->belongsTo(Workspace::class); }
    public function owner() { return $this->belongsTo(User::class, 'owner_id'); }
    public function channels() { return $this->hasMany(Channel::class); }
}