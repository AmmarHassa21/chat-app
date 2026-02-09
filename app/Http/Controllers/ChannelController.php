<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    // 1. Get All Channels (Jo user dekh sakta hai)
    public function index()
    {
        $userId = (string) auth()->id();

        // MongoDB query: Public channels YA wo channels jahan user member hai
        $channels = Channel::where('type', 'public')
            ->orWhere('member_ids', 'all', [$userId]) 
            ->get();

        return response()->json($channels);
    }

    // 2. Create Channel (Public, Private, Direct)
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required_unless:type,direct|string|max:255',
                'type' => 'required|in:public,private,direct',
                'receiver_id' => 'required_if:type,direct',
            ]);

            $currentUserId = (string) auth()->id();
            $memberIds = [$currentUserId];

            if ($request->type === 'direct') {
                $receiverId = (string) $request->receiver_id;
                $memberIds[] = $receiverId;
                // DM ka unique name (alphabetical order taaki duplicate na banein)
                $ids = [$currentUserId, $receiverId];
                sort($ids);
                $name = 'dm_' . implode('_', $ids);
            } else {
                $name = $request->name;
            }

            $channel = Channel::create([
                'name'        => $name,
                'type'        => $request->type,
                'description' => $request->description ?? '',
                'team_id'     => $request->team_id ?? 'default',
                'created_by'  => $currentUserId,
                'member_ids'  => $memberIds,
            ]);

            return response()->json(['status' => 'success', 'channel' => $channel], 201);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    // 3. Show Channel (FIXED LOGIC)
    public function show($id)
    {
        $channel = Channel::find($id);
        if (!$channel) return response()->json(['message' => 'Channel not found'], 404);

        $userId = (string) auth()->id();
        $type = strtolower($channel->type);

        // A. Agar Public hai toh access allow
        if ($type === 'public') {
            return response()->json($channel);
        }

        // B. Private ya Direct ke liye membership check karein
        $members = $channel->member_ids ?? [];
        
        // Ensure karein ke members array strings ka ho
        $isMember = in_array($userId, array_map('strval', $members));

        if (!$isMember) {
            return response()->json([
                'message' => 'Forbidden channel access.',
                'debug' => [
                    'your_id' => $userId,
                    'channel_type' => $type,
                    'is_member' => $isMember
                ]
            ], 403);
        }

        return response()->json($channel);
    }

    // 4. Update
    public function update(Request $request, $id)
    {
        $channel = Channel::find($id);
        if (!$channel) return response()->json(['message' => 'Not found'], 404);

        if ((string)$channel->created_by !== (string)auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $channel->update($request->only(['name', 'description', 'type']));
        return response()->json(['status' => 'success', 'channel' => $channel]);
    }

    // 5. Delete
    public function destroy($id)
    {
        $channel = Channel::find($id);
        if ($channel && $channel->delete()) {
            return response()->json(['message' => 'Deleted successfully']);
        }
        return response()->json(['message' => 'Not found'], 404);
    }
}