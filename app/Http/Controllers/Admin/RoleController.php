<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Role\StoreRoleRequest;
use App\Http\Requests\Admin\Role\UpdateRoleRequest;
use App\Http\Resources\Common\MetaResource;
use App\Models\Role;
use App\Services\Permission\PermissionService;
use App\Services\Role\RoleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class RoleController extends Controller
{
    /**
     * @param RoleService $roleService
     * @param PermissionService $permissionService
     */
    public function __construct(
        protected readonly RoleService $roleService,
        protected readonly PermissionService $permissionService,
    ) {}

    /**
     * @return Response
     */
    public function index(): Response
    {
        $records = $this->roleService->paginate();

        return Inertia::render('role/Index', [
            'records' => $records->items(),
            'meta' => MetaResource::make($records),
        ]);
    }

    /**
     * @return Response
     */
    public function create(): Response
    {
        $permissions = $this->permissionService->getAll();

        return Inertia::render('role/Create', [
            'permissions' => $permissions,
        ]);
    }

    /**
     * @param StoreRoleRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRoleRequest $request): RedirectResponse
    {
        $item = $this->roleService->create($request->toArray());
        $item->givePermissions($request->input('permissions'));

        return redirect()->route('admin.roles.show', $item->id)->with('success', 'Role created successfully');
    }

    /**
     * @param Role $role
     * @return Response
     */
    public function show(Role $role): Response
    {
        $role->load(['permissions']);
        $permissions = $this->permissionService->getAll();

        return Inertia::render('role/Show', [
            'role' => $role,
            'permissions' => $permissions,
        ]);
    }

    /**
     * @param Role $role
     * @param UpdateRoleRequest $request
     * @return RedirectResponse
     */
    public function update(Role $role, UpdateRoleRequest $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $this->roleService->update($role->id, $request->only(['name', 'status']));
            $role->permissions()->sync($request->validated('permissions'));
            $role->refresh();
            DB::commit();

            return redirect()->route('admin.roles.show', $role->id)->with('success', 'Role updated successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * @param Role $role
     * @return RedirectResponse
     */
    public function destroy(Role $role): RedirectResponse
    {
        $role->revokeAllPermissions();
        $this->roleService->delete($role->id);

        return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully.');
    }
}
