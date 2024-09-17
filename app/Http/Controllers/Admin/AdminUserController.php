<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.user', compact('users'));
    }

    public function destroy(string $id)
    {
        try {
            $user = User::find($id);
            $user->delete();
            return redirect()->route('user.index')->with('success', 'User berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('user.index')->with('error', 'User gagal dihapus');
        }
    }
}
