<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim(
            $request->string('search')->toString()
        );

        $role = $request->string('role')->toString();

        $roles = Role::query()
            ->orderBy('name')
            ->get();

        $users = User::query()
            ->with([
                'role',
                'buildings',
            ])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where(
                            'employee_number',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhere(
                            'name',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhere(
                            'username',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhere(
                            'email',
                            'like',
                            '%' . $search . '%'
                        );
                });
            })
            ->when($role !== '', function ($query) use ($role) {
                $query->where(
                    'role_id',
                    $role
                );
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view(
            'users.index',
            [
                'users' => $users,
                'roles' => $roles,
                'search' => $search,
                'role' => $role,
            ]
        );
    }

    public function create(): View
    {
        $roles = Role::query()
            ->orderBy('name')
            ->get();

        $buildings = Building::query()
            ->orderBy('name')
            ->get();

        return view(
            'users.create',
            [
                'roles' => $roles,
                'buildings' => $buildings,
            ]
        );
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'role_id' => [
                'required',
                'integer',
                'exists:roles,id',
            ],

            'employee_number' => [
                'required',
                'string',
                'max:50',
                'unique:users,employee_number',
            ],

            'name' => [
                'required',
                'string',
                'max:150',
            ],

            'username' => [
                'required',
                'string',
                'max:100',
                'unique:users,username',
            ],

            'email' => [
                'required',
                'email',
                'max:150',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],

            'building_ids' => [
                'nullable',
                'array',
            ],

            'building_ids.*' => [
                'integer',
                'exists:buildings,id',
            ],
        ]);

        $role = Role::findOrFail(
            $validated['role_id']
        );

        $buildingIds = $validated['building_ids'] ?? [];

        if (
            $role->name === 'Building Coordinator' &&
            count($buildingIds) === 0
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'building_ids' => 'Please select at least one building for a Building Coordinator.',
                ]);
        }

        if (
            $role->name !== 'Building Coordinator'
        ) {
            $buildingIds = [];
        }

        DB::transaction(function () use (
            $validated,
            $buildingIds
        ) {
            $user = User::create([
                'role_id' => $validated['role_id'],
                'employee_number' => $validated['employee_number'],
                'name' => $validated['name'],
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => $validated['password'],
            ]);

            if ($buildingIds !== []) {
                $user->buildings()->sync(
                    $buildingIds
                );
            }
        });

        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'User created successfully.'
            );
    }

    public function edit(User $user): View
    {
        $user->load([
            'role',
            'buildings',
        ]);

        $roles = Role::query()
            ->orderBy('name')
            ->get();

        $buildings = Building::query()
            ->orderBy('name')
            ->get();

        return view(
            'users.edit',
            [
                'user' => $user,
                'roles' => $roles,
                'buildings' => $buildings,
            ]
        );
    }

    public function update(
        Request $request,
        User $user
    ): RedirectResponse {
        $validated = $request->validate([
            'role_id' => [
                'required',
                'integer',
                'exists:roles,id',
            ],

            'employee_number' => [
                'required',
                'string',
                'max:50',
                'unique:users,employee_number,' . $user->id,
            ],

            'name' => [
                'required',
                'string',
                'max:150',
            ],

            'username' => [
                'required',
                'string',
                'max:100',
                'unique:users,username,' . $user->id,
            ],

            'email' => [
                'required',
                'email',
                'max:150',
                'unique:users,email,' . $user->id,
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],

            'building_ids' => [
                'nullable',
                'array',
            ],

            'building_ids.*' => [
                'integer',
                'exists:buildings,id',
            ],
        ]);

        $role = Role::findOrFail(
            $validated['role_id']
        );

        /*
         * Prevent the last Admin account from
         * being changed to another role.
         */
        if (
            $user->role?->name === 'Admin' &&
            $role->name !== 'Admin'
        ) {
            $adminCount = User::query()
                ->whereHas('role', function ($query) {
                    $query->where(
                        'name',
                        'Admin'
                    );
                })
                ->count();

            if ($adminCount <= 1) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'role_id' => 'The last Admin account cannot be changed to another role.',
                    ]);
            }
        }

        $buildingIds = $validated['building_ids'] ?? [];

        if (
            $role->name === 'Building Coordinator' &&
            count($buildingIds) === 0
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'building_ids' => 'Please select at least one building for a Building Coordinator.',
                ]);
        }

        if (
            $role->name !== 'Building Coordinator'
        ) {
            $buildingIds = [];
        }

        DB::transaction(function () use (
            $validated,
            $buildingIds,
            $user
        ) {
            $user->role_id = $validated['role_id'];
            $user->employee_number = $validated['employee_number'];
            $user->name = $validated['name'];
            $user->username = $validated['username'];
            $user->email = $validated['email'];

            if (
                ! empty($validated['password'])
            ) {
                $user->password = $validated['password'];
            }

            $user->save();

            $user->buildings()->sync(
                $buildingIds
            );
        });

        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'User updated successfully.'
            );
    }

    public function destroy(
        User $user
    ): RedirectResponse {
        /*
         * Prevent an Admin from deleting their
         * own account.
         */
        if (
            $user->id === auth()->id()
        ) {
            return redirect()
                ->route('users.index')
                ->with(
                    'error',
                    'You cannot delete your own account.'
                );
        }

        /*
         * Prevent deletion of the last Admin account.
         */
        if (
            $user->role?->name === 'Admin'
        ) {
            $adminCount = User::query()
                ->whereHas('role', function ($query) {
                    $query->where(
                        'name',
                        'Admin'
                    );
                })
                ->count();

            if ($adminCount <= 1) {
                return redirect()
                    ->route('users.index')
                    ->with(
                        'error',
                        'The last Admin account cannot be deleted.'
                    );
            }
        }

        /*
         * Existing protection:
         * users with reservation records cannot be deleted.
         */
        if (
            $user->reservations()->exists()
        ) {
            return redirect()
                ->route('users.index')
                ->with(
                    'error',
                    'This user cannot be deleted because they have reservation records.'
                );
        }

        DB::transaction(function () use ($user) {
            $user->buildings()->detach();
            $user->delete();
        });

        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'User deleted successfully.'
            );
    }
}