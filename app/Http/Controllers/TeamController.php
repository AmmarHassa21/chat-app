<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $userId = (string) Auth::id();
        
        // Agar workspace_id di gayi hai toh uski teams dikhao, warna user ki apni teams
        if ($request->filled('workspace_id')) {
            $teams = Team::where('workspace_id', $request->workspace_id)->get();
        } else {
            $teams = Team::where('owner_id', $userId)
                         ->orWhere('member_ids', 'all', [$userId])
                         ->get();
        }

        return response()->json($teams);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'workspace_id' => ['required', 'string'], // MongoDB IDs are strings
                'name' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'member_ids' => ['nullable', 'array'],
            ]);

            $userId = (string) Auth::id();

            // Workspace check
            $workspace = Workspace::find($request->workspace_id);
            if (!$workspace) {
                return response()->json(['message' => 'Workspace not found'], 404);
            }

            // Member list taiyar karein
            $members = $request->member_ids ?? [];
            if (!in_array($userId, $members)) {
                $members[] = $userId;
            }

            // Team Create karein (Attach use nahi karna)
            $team = Team::create([
                'workspace_id' => $request->workspace_id,
                'owner_id'     => $userId,
                'name'         => $request->name,
                'description'  => $request->description,
                'member_ids'   => $members,
            ]);

            return response()->json($team, 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    public function show($id)
    {
        $team = Team::with('channels')->find($id);
        if (!$team) return response()->json(['message' => 'Team not found'], 404);
        
        return response()->json($team);
    }

    public function update(Request $request, $id)
    {
        $team = Team::find($id);
        if (!$team) return response()->json(['message' => 'Team not found'], 404);

        if ($team->owner_id !== (string) Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $team->update($request->only(['name', 'description']));
        return response()->json($team);
    }

    public function destroy($id)
    {
        $team = Team::find($id);
        if ($team && $team->delete()) {
            return response()->json(['message' => 'Deleted successfully']);
        }
        return response()->json(['message' => 'Not found'], 404);
    }
}