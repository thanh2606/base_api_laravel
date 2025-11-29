<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ConstPaginate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Profile\ChangePasswordRequest;
use App\Http\Requests\Admin\Profile\DeleteProfileRequest;
use App\Http\Requests\Admin\Profile\StoreAdminRequest;
use App\Http\Requests\Admin\Profile\UpdateProfile;
use App\Http\Resources\Common\MetaResource;
use App\Models\Admin;
use App\Services\Admin\AdminService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Prettus\Validator\Exceptions\ValidatorException;

class AdminController extends Controller
{
    public function __construct(
        private readonly AdminService $adminService
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        $limit = request()->query('perPage', ConstPaginate::PER_PAGE);
        $keyword = request()->query('search');
        $records = $this->adminService->search($keyword, $limit);

        return Inertia::render('admin/Index', [
            'records' => $records->items(),
            'meta' => MetaResource::make($records),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render('admin/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreAdminRequest $request
     * @return RedirectResponse
     */
    public function store(StoreAdminRequest $request): RedirectResponse
    {
        $admin = $this->adminService->create($request->validated());

        return redirect()->route('admin.admins.show', $admin->id)->with('success', 'Admin created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param Admin $admin
     * @return Response
     */
    public function show(Admin $admin): Response
    {
        return Inertia::render('admin/Show', [
            'admin' => $admin,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Admin $admin
     * @param UpdateProfile $request
     * @return RedirectResponse
     */
    public function update(Admin $admin, UpdateProfile $request): RedirectResponse
    {
        $this->adminService->update($request->validated(), $admin->id);

        return redirect()->route('admin.admins.show', $admin->id)->with('success', 'Profile updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Admin $admin
     * @return RedirectResponse
     */
    public function destroy(Admin $admin): RedirectResponse
    {
        $admin->delete();

        return redirect()->route('admin.admins.index')->with('success', 'Profile deleted successfully.');
    }

    /**
     * @param int $id
     * @param ChangePasswordRequest $request
     * @return RedirectResponse
     * @throws ValidatorException
     */
    public function changePassword(int $id, ChangePasswordRequest $request): RedirectResponse
    {
        $password = $request->input('password');
        $this->adminService->changePassword($id, $password);

        return redirect()->route('admin.profile')->with('success', 'Password changed successfully.');
    }

    /**
     * @param DeleteProfileRequest $request
     * @return RedirectResponse
     *
     * @throws ValidationException
     */
    public function deleteProfile(DeleteProfileRequest $request): RedirectResponse
    {
        $admin = Auth::guard('admin')->user();
        $password = Hash::make($request->input('password'));

        if ($admin->password !== $password || !$admin) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        $this->destroy($admin);

        return redirect()->route('admin.admins.index')->with('success', 'Profile deleted successfully.');
    }

    /**
     * @return RedirectResponse
     */
    public function multiDelete(): RedirectResponse
    {
        $ids = request()->input('ids', []);
        $this->adminService->multiDelete($ids);

        return redirect()->route('admin.admins.index')
            ->with('success', 'Xóa tài khoản thành công.');
    }
}
