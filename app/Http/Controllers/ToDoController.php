<?php

namespace App\Http\Controllers;

use App\Models\ToDoList;
use Illuminate\Http\Request;

class ToDoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(ToDoList::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'task' => 'required',
        ]);

        ToDoList::create($request->all());

        return response()->json(['message' => 'success'], 201);
    }

    /**
     * Display the specified resource.-
     */
    public function show(string $id)
    {
        $todo = ToDoList::findOrFail($id);

        return response()->json($todo, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'task' => 'required',
        ]);

        $todo = ToDoList::findOrFail($id);

        $todo->update($request->all());

        return response()->json(['messsage' => 'updated', 'data' => $request->all()]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $todo = ToDoList::findOrFail($id);

        $todo->delete();

        return response()->json(['messsage' => 'Deleted']);
    }
}