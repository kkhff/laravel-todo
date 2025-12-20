<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index()
    {
        $todos = Todo::orderBy('is_completed', 'asc') 
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('todos.index', compact('todos'));
    }

    public function store(Request $request)
    {
        $request->validate(['title' => 'required']);
        Todo::create($request->all());
        return redirect()->back()->with('success', 'Tugas berhasil ditambahkan!');
    }

    public function update(Request $request, Todo $todo)
    {
        if ($request->has('toggle_status')) {
            $todo->is_completed = !$todo->is_completed;
            $todo->save();
            
            return redirect()->back()->with('success', 'Status tugas diperbarui!');
        }

        $request->validate([
            'title' => 'required|max:255',
        ]);

        $todo->update($request->all());

        return redirect()->back()->with('success', 'Tugas berhasil diperbarui!');
    }

    public function destroy(Todo $todo)
    {
        $todo->delete();
        return redirect()->back()->with('success', 'Tugas berhasil dihapus!');
    }
}