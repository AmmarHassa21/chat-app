<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkspaceController extends Controller
{
    // 1. Saare Workspaces dikhana (Jahan user owner ya member hai)
    public function index()
    {
        $userId = (string) Auth::id();

        $workspaces = Workspace::where('owner_id', $userId)
            ->orWhere('member_ids', 'all', [$userId])
            ->get();

        return response()->json($workspaces);
    }

    // 2. Naya Workspace banana
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'member_ids' => 'nullable|array',
        ]);

        $userId = (string) Auth::id();
        
        // Members list taiyar karein (Owner lazmi shamil hoga)
        $members = $request->member_ids ?? [];
        if (!in_array($userId, $members)) {
            $members[] = $userId;
        }

        $workspace = Workspace::create([
            'name' => $request->name,
            'description' => $request->description,
            'owner_id' => $userId,
            'member_ids' => $members,
        ]);

        return response()->json([
            'status' => 'success',
            'workspace' => $workspace
        ], 201);
    }

    // 3. Single Workspace details
    public function show($id)
    {
        $workspace = Workspace::find($id);
        if (!$workspace) return response()->json(['message' => 'Workspace not found'], 404);

        $userId = (string) Auth::id();
        if ($workspace->owner_id !== $userId && !in_array($userId, $workspace->member_ids ?? [])) {
            return response()->json(['message' => 'Forbidden access.'], 403);
        }

        return response()->json($workspace);
    }

    // 4. Update Workspace
    public function update(Request $request, $id)
    {
        $workspace = Workspace::find($id);
        if (!$workspace) return response()->json(['message' => 'Not found'], 404);

        if ($workspace->owner_id !== (string) Auth::id()) {
            return response()->json(['message' => 'Only owner can update.'], 403);
        }

        $workspace->update($request->only(['name', 'description']));
        return response()->json($workspace);
    }

    // 5. Delete Workspace
    public function destroy($id)
    {
        $workspace = Workspace::find($id);
        if ($workspace && $workspace->delete()) {
            return response()->json(['message' => 'Workspace deleted']);
        }
        return response()->json(['message' => 'Not found'], 404);
    }
}