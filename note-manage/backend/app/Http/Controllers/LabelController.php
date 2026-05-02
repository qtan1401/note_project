<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LabelController extends Controller
{
    public function index()
    {
        $user_id = \App\Models\User::first()->id ?? 1;
        $labels = Label::where('user_id', $user_id)->get();
        return response()->json([
            'status' => 'success',
            'data' => $labels
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user_id = \App\Models\User::first()->id ?? 1;
        $label = Label::create([
            'user_id' => $user_id,
            'name' => $request->name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Label created successfully',
            'data' => $label
        ], 201);
    }

    public function update(Request $request, Label $label)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $label->update(['name' => $request->name]);

        return response()->json([
            'status' => 'success',
            'message' => 'Label updated successfully',
            'data' => $label
        ]);
    }

    public function destroy(Label $label)
    {
        $label->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Label deleted successfully'
        ]);
    }
}
