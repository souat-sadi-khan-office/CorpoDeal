<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use App\Repositories\Interface\RoleRepositoryInterface;

class RoleController extends Controller
{
    protected $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    // List all roles
    public function index(Request $request)
    {

        if (auth()->guard('admin')->user()->hasPermissionTo('roles.view') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        if ($request->ajax()) {

            $roles = $this->roleRepository->getAllRoles();
            return Datatables::of($roles)
                ->addIndexColumn()
                ->addColumn('action', function ($model) {
                    return view('backend.roles.action', compact('model'));
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('backend.roles.index');
    }

    // Show create form
    public function create()
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('roles.create') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $permissions = Permission::all();
        return view('backend.roles.create', compact('permissions'));
    }

    // Store a new role
    public function store(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('roles.create') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $request->validate([
            'name' => 'required|unique:roles',
        ]);

        $this->roleRepository->createRole($request);

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully');
    }

    // Show edit form
    public function edit($id)
    {

        if (auth()->guard('admin')->user()->hasPermissionTo('roles.update') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $role = $this->roleRepository->findRoleById($id);
        $role_permissions = [];
		foreach ($role->permissions as $role_perm) {
			$role_permissions[] = $role_perm->name;
		}

        $permissions = $this->roleRepository->getAllPermission();
        
        return view('backend.roles.edit', compact('role', 'permissions', 'role_permissions'));
    }

    // Update an existing role
    public function update(Request $request, $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('roles.update') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $data = $request->validate([
            'name' => 'required|unique:roles,name,' . $id,
            'permissions' => 'required'
        ]);

        $this->roleRepository->updateRole($id, $data);

        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully');
    }

    // Delete a role
    public function destroy($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('roles.delete') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $this->roleRepository->deleteRole($id);

        return response()->json([
            'status' => true, 
            'load' => true,
            'message' => "Role And Permission Delete"
        ]);
    }
}