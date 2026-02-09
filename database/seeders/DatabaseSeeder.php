<?php

namespace Database\Seeders;

use App\Models\Channel;
use App\Models\Message;
use App\Models\Team;
use App\Models\Workspace;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
            'role' => 'admin',
        ]);

        $workspace = Workspace::create([
            'name' => 'Demo Workspace',
            'description' => 'Seeded workspace for chat demo',
            'owner_id' => $admin->id,
        ]);
        $workspace->members()->attach($admin->id, ['role' => 'owner']);

        $team = Team::create([
            'workspace_id' => $workspace->id,
            'owner_id' => $admin->id,
            'name' => 'Core Team',
            'description' => 'Default team for demo',
        ]);
        $team->members()->attach($admin->id, ['role' => 'owner']);

        $channel = Channel::create([
            'team_id' => $team->id,
            'created_by' => $admin->id,
            'name' => 'general',
            'type' => 'public',
        ]);

        Message::create([
            'channel_id' => $channel->id,
            'user_id' => $admin->id,
            'body' => 'Welcome to the chat workspace!',
        ]);
    }
}
