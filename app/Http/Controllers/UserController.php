<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //
    public function index()
    {
        return view('pages.user.index');
    }

    public function getUsers()
    {
        try {
            $users = User::all();
            return response()->json($users, 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
    public function addUser(Request $request)
    {
        try {
            $validation = $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
                'role' => 'required',
                // 'is_active' => 'required',
            ]);
            $validation['password'] = Hash::make($validation['password']);
            $validation['is_active'] = 1;
            User::create($validation);
            return response()->json(['message' => 'user created successfully'], 201);
        } catch (\Illuminate\Validation\ValidationException $th) {
            // Menangani error validasi
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $th->errors(), // Menyertakan pesan error validasi yang terperinci
            ], 422); // Status 422 untuk error validasi
        } catch (\Throwable $th) {
            // Tangani error lain
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function updateUser($id)
    {
        try {
            $validation = request()->validate([
                'name' => 'required',
                'email' => 'required|email',
                'role' => 'required',
                'is_active' => 'required|boolean',
                'password' => 'nullable|string', // Tidak wajib, tapi harus valid jika diisi
            ]);

            // Hash password jika diisi
            if (!empty($validation['password'])) {
                $validation['password'] = Hash::make($validation['password']);
            }




            User::findOrFail($id);
            User::where('id', $id)->update($validation);
            return response()->json(['message' => 'user updated successfully'], 201);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
    public function deleteUser($id)
    {
        try {
            User::findOrFail($id);
            User::where('id', $id)->delete();
            return response()->json(['message' => 'user deleted successfully'], 201);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
    function tes()
    {
        // Hash::make($validation['password']);
        dd(Hash::make('123'));
    }
}
