<?php
namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        // paginasi dipaksa max 25 data per halaman awal
        return view('dashboard.employees', [
            'employees' => Employee::oldest()->paginate(25)
        ]);
    }

    public function store(Request $request)
    {
        $validData = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'segment' => 'required|string|max:255',
            'month' => 'required|string|max:255',
            'regional' => 'required|string|max:255'
        ]);

        $employee = Employee::create($validData);

        // Trigger notification for all users
        foreach (\App\Models\User::all() as $u) {
            \App\Models\Notification::create([
                'user_id' => $u->id,
                'title' => 'Pegawai Baru Bergabung',
                'message' => $employee->name . ' telah bergabung sebagai ' . $employee->role . ' di ' . $employee->regional . '.',
                'type' => 'employee',
            ]);
        }

        return redirect()->back()->with('success', 'Berhasil menambah data pegawai!');
    }

    public function update(Request $request, Employee $employee)
    {
        $validData = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'segment' => 'required|string|max:255',
            'month' => 'required|string|max:255',
            'regional' => 'required|string|max:255'
        ]);

        $employee->update($validData);

        return redirect()->back()->with('success', 'Data pegawai berhasil diperbarui!');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->back()->with('success', 'Pegawai berhasil dihapus!');
    }
}
