<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Note::with('labels');

        // Search logic
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%")
                    ->orWhereHas('labels', function ($ql) use ($search) {
                        $ql->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Filtering by label
        if ($request->has('label_id')) {
            $query->whereHas('labels', function ($q) use ($request) {
                $q->where('labels.id', $request->label_id);
            });
        }

        $notes = $query->orderBy('is_pinned', 'desc')
            ->orderBy('pinned_at', 'desc')
            ->orderBy('updated_at', 'desc')
            ->get();

        // Hide content for locked notes
        $notes->each(function ($note) {
            if ($note->is_locked) {
                $note->content = null;
            }
        });

        return response()->json([
            'status' => 'success',
            'data' => $notes
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'label_ids' => 'nullable|array',
            'label_ids.*' => 'exists:labels,id',
            'is_locked' => 'nullable|boolean',
            'password' => 'nullable|string|min:4',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user_id = \App\Models\User::first()->id ?? 1;

        $noteData = [
            'user_id' => $user_id,
            'title' => $request->title,
            'content' => $request->content,
            'color' => $request->color ?? '#ffffff',
            'is_locked' => $request->is_locked ?? false,
        ];

        // Hash password if locking the note
        if ($request->is_locked && $request->password) {
            $noteData['password'] = Hash::make($request->password);
        }

        $note = Note::create($noteData);

        if ($request->has('label_ids')) {
            $note->labels()->sync($request->label_ids);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Note created successfully',
            'data' => $note->load('labels')
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note)
    {
        $note->load('labels');

        // If note is locked, hide content
        if ($note->is_locked) {
            $note->content = null;
        }

        return response()->json([
            'status' => 'success',
            'data' => $note
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Note $note)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'content' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'label_ids' => 'nullable|array',
            'label_ids.*' => 'exists:labels,id',
            'is_locked' => 'nullable|boolean',
            'password' => 'nullable|string|min:4',
            'current_password' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // If note is currently locked, require current_password to update
        if ($note->is_locked && $note->getOriginal('password')) {
            if (!$request->current_password || !Hash::check($request->current_password, $note->getOriginal('password'))) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Wrong password. Please try again.'
                ], 403);
            }
        }

        $updateData = $request->only(['title', 'content', 'color']);

        // Handle lock toggle
        if ($request->has('is_locked')) {
            $updateData['is_locked'] = $request->is_locked;

            if ($request->is_locked && $request->password) {
                $updateData['password'] = Hash::make($request->password);
            } elseif (!$request->is_locked) {
                $updateData['password'] = null;
            }
        }

        $note->update($updateData);

        if ($request->has('label_ids')) {
            $note->labels()->sync($request->label_ids);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Note updated successfully',
            'data' => $note->load('labels')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Note $note)
    {
        // If note is locked, require password to delete
        if ($note->is_locked && $note->getOriginal('password')) {
            $password = $request->input('password') ?? $request->header('X-Note-Password');
            if (!$password || !Hash::check($password, $note->getOriginal('password'))) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Wrong password. Please try again.'
                ], 403);
            }
        }

        $note->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Note deleted successfully'
        ]);
    }

    /**
     * Toggle pin status of a note.
     */
    public function togglePin(Note $note)
    {
        $note->is_pinned = !$note->is_pinned;
        $note->pinned_at = $note->is_pinned ? now() : null;
        $note->save();

        return response()->json([
            'status' => 'success',
            'message' => $note->is_pinned ? 'Note pinned' : 'Note unpinned',
            'data' => $note
        ]);
    }

    /**
     * Verify password for a locked note and return content.
     */
    public function verifyPassword(Request $request, Note $note)
    {
        if (!$note->is_locked) {
            return response()->json([
                'status' => 'error',
                'message' => 'This note is not locked.'
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Password is required.'
            ], 422);
        }

        if (!Hash::check($request->password, $note->getOriginal('password'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'Wrong password. Please try again.'
            ], 403);
        }

        // Password correct — return full note with content
        $note->load('labels');

        return response()->json([
            'status' => 'success',
            'message' => 'Password verified successfully.',
            'data' => [
                'id' => $note->id,
                'title' => $note->title,
                'content' => $note->getOriginal('content'),
                'color' => $note->color,
                'is_pinned' => $note->is_pinned,
                'is_locked' => $note->is_locked,
                'labels' => $note->labels,
                'created_at' => $note->created_at,
                'updated_at' => $note->updated_at,
            ]
        ]);
    }
}
