<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 * name="Todos V1",
 * description="Dokumentasi API untuk manajemen tugas harian (Todo List)"
 * )
 */
class TodoController extends Controller
{
    /**
     * @OA\Get(
     * path="/v1/todos",
     * operationId="getTodoListV1",
     * tags={"Todos V1"},
     * summary="Ambil daftar tugas dari DB",
     * description="Mengambil semua tugas yang tersimpan di database",
     * @OA\Response(
     * response=200,
     * description="Daftar tugas berhasil diambil"
     * )
     * )
     */
    public function index()
    {
        $todos = Todo::orderBy('is_completed', 'asc')
                    ->orderBy('created_at', 'desc')
                    ->get();

        return response()->json([
            'status' => 'success',
            'todos' => $todos
        ]);
    }

    /**
     * @OA\Post(
     * path="/v1/todos",
     * operationId="storeTodoV1",
     * tags={"Todos V1"},
     * summary="Tambah tugas baru",
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"title"},
     * @OA\Property(property="title", type="string", example="Belajar Laravel API")
     * )
     * ),
     * @OA\Response(response=201, description="Berhasil simpan")
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $todo = Todo::create([
            'title' => $request->title,
            'description' => $request->description,
            'is_completed' => false
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Tugas berhasil ditambahkan!',
            'todo' => $todo
        ], 201);

    }

    /**
     * @OA\Patch(
     * path="/v1/todos/{todo}/toggle",
     * operationId="toggleTodoStatusV1",
     * tags={"Todos V1"},
     * summary="Toggle status is_completed",
     * description="Endpoint khusus untukganti status tanpa refresh",
     * @OA\Parameter(name="todo", in="path", required=true, @OA\Schema(type="integer")),
     * @OA\Response(response=200, description="Status diperbarui"),
     * @OA\Response(response=404, description="Tugas tidak ditemukan")
     * )
     */
    public function toggle(Todo $todo)
    {
        $todo->is_completed = !$todo->is_completed;
        $todo->save();

        return response()->json([
            'message' => 'Status tugas diperbarui!',
            'is_completed' => $todo->is_completed
        ]);
    }

    /**
     * @OA\Put(
     * path="/v1/todos/{todo}",
     * operationId="updateTodoV1",
     * tags={"Todos V1"},
     * summary="Update judul tugas",
     * @OA\Parameter(name="todo", in="path", required=true, @OA\Schema(type="integer")),
     * @OA\RequestBody(
     * @OA\JsonContent(
     * @OA\Property(property="title", type="string"),
     * @OA\Property(property="description", type="string")
     * )
     * ),
     * @OA\Response(response=200, description="Data diupdate")
     * )
     */
    public function update(Request $request, Todo $todo)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable|string'
        ]);

        $todo->update($request->all());

        return response()->json([
            'message' => 'Tugas berhasil diperbarui!',
            'todo' => $todo
        ]);
    }
    
    /**
     * @OA\Delete(
     * path="/v1/todos/{todo}",
     * operationId="deleteTodoV1",
     * tags={"Todos V1"},
     * summary="Hapus tugas",
     * @OA\Parameter(name="todo", in="path", required=true, @OA\Schema(type="integer")),
     * @OA\Response(response=200, description="Data dihapus")
     * )
     */
    public function destroy(Todo $todo)
    {
        $todo->delete();

        return response()->json([
            'message' => 'Tugas berhasil dihapus'
        ]);
    }
}
