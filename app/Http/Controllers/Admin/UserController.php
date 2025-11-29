<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ConstPaginate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\StoreUserRequest;
use App\Http\Requests\Admin\User\UpdateUserRequest;
use App\Http\Resources\Common\MetaResource;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    /**
     * @param UserService $userService
     */
    public function __construct(
        private readonly UserService $userService
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
        $records = $this->userService->search($keyword, $limit);

        return Inertia::render('user/Index', [
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
        return Inertia::render('user/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreUserRequest $request
     * @return RedirectResponse
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $user = $this->userService->create($request->validated());

        return redirect()->route('admin.users.show', $user->id)->with('success', 'User created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return Response
     */
    public function show(User $user): Response
    {
        return Inertia::render('user/Show', [
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param User $user
     * @param UpdateUserRequest $request
     * @return RedirectResponse
     */
    public function update(User $user, UpdateUserRequest $request): RedirectResponse
    {
        $this->userService->update($request->validated(), $user->id);

        return redirect()->route('admin.users.show', $user->id)->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return RedirectResponse;
     */
    public function destroy(User $user): RedirectResponse
    {
        $this->userService->delete($user->id);

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
