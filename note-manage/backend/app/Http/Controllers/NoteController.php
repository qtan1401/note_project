<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notes = Note::orderBy('updated_at', 'desc')->get();
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
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user_id = \App\Models\User::first()->id ?? 1;
        $note = Note::create([
            'user_id' => $user_id,
            'title' => $request->title,
            'content' => $request->content,
            'color' => $request->color ?? '#ffffff',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Note created successfully',
            'data' => $note
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note)
    {

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
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $note->update($request->only(['title', 'content', 'color']));

        return response()->json([
            'status' => 'success',
            'message' => 'Note updated successfully',
            'data' => $note
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {

        $note->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Note deleted successfully'
        ]);
    }
}
