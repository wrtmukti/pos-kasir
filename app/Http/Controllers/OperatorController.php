<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class OperatorController extends Controller
{

    public function employee()
    {
        $users = User::all();
        return view('admin.operator.employee.index', compact('users'));
    }
    public function employeeDestroy($id)
    {
        User::destroy($id);
        return redirect()->to('/admin/operator/employee')->with('danger', 'item dihapus:(');
    }
}
