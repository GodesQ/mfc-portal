<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\UpdateProfileServiceRequest;
use App\Http\Requests\User\StoreRequest;
use App\Models\Section;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\UserMissionaryService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, string $section)
    {
        $breadcrumb = "Users";
        // Define section mappings
        $sectionMap = [
            'kids' => ['section_id' => 1, 'btn_color' => 'btn-danger'],
            'youth' => ['section_id' => 2, 'btn_color' => 'btn-primary'],
            'singles' => ['section_id' => 3, 'btn_color' => 'btn-success'],
            'handmaids' => ['section_id' => 4, 'btn_color' => 'btn-red'],
            'servants' => ['section_id' => 5, 'btn_color' => 'btn-warning'],
            'couples' => ['section_id' => 6, 'btn_color' => 'btn-info'],
        ];

        // Set default section values
        $btn_color = 'btn-info';
        $section_id = null;

        if (array_key_exists($section, $sectionMap)) {
            $section_id = $sectionMap[$section]['section_id'];
            $btn_color = $sectionMap[$section]['btn_color'];
        }

        // Query the users based on the section
        $users = $section_id ? User::where('section_id', $section_id)->get() : User::all();

        if ($request->ajax()) {
            return DataTables::of($users)
                ->editColumn('section', function ($row) {
                    return $row->section->name ?? 'N/A';
                })
                ->addColumn('actions', function ($user) {
                    $actions = "<div class='hstack gap-2'>
                    <button type='button' class='btn btn-soft-success btn-sm edit-btn' id='" . $user->id . "' data-bs-toggle='tooltip' data-bs-placement='top' title='Edit'><i class='ri-pencil-fill align-bottom'></i></button>
                    <button type='button' class='btn btn-soft-danger btn-sm remove-btn' id='" . $user->id . "' data-bs-toggle='tooltip' data-bs-placement='top' title='Remove'><i class='ri-delete-bin-fill align-bottom'></i></button>
                </div>";

                    return $actions;
                })
                ->addColumn('name', function ($user) {
                    return $user->first_name . ' ' . $user->last_name;
                })
                ->rawColumns(['actions', 'name'])
                ->make(true);
        }

        // Retrieve sections and roles
        $sections = Section::all();
        $roles = Role::all();


        // Return view with data
        return view('pages.users.index', compact('section', 'breadcrumb', 'btn_color', 'sections', 'roles'));
    }

    public function profile(string $id)
    {
        $sections = Section::get();
        $user = User::findOrFail($id);

        $user->load('missionary_services');

        return view('pages.profile.index', compact('user', 'sections'));
    }

    public function updateProfile(Request $request, string $id)
    {
        $user_data = $request->except('_token', '_method', 'god_given_skills');
        $user = User::where('id', $id)->with('user_details')->firstOrFail();

        $user->update(array_merge($user_data));

        $skills = [];

        if ($request->has('god_given_skills') && is_array($request->god_given_skills)) {
            foreach ($request->god_given_skills as $key => $skill) {
                array_push($skills, $skill);
            }
        }

        if ($user->user_details) {
            $user->user_details->update(array_merge($user_data, ['god_given_skills' => $skills]));
        } else {
            UserDetail::create(array_merge($user_data, ['user_id' => $user->id, 'god_given_skills' => $skills]));
        }

        return back()->withSuccess("Profile Updated Successfully");
    }

    public function updateProfileService(UpdateProfileServiceRequest $request, $id)
    {
        $user = User::findOrFail($id);

        foreach ($request->service_category as $key => $category) {
            UserMissionaryService::updateOrCreate([
                "user_id" => $user->id,
                "service_category" => $category,
                "service_type" => $request->service_type[$key],
                "section" => $request->section[$key],
                "area" => $request->service_area[$key],
            ], []);
        }

        return back()->withSuccess("User service updated successfully.");
    }

    public function updatePassword(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if (!Hash::check($request->old_password, $user->password))
            return back()->with('fail', "The user password is not match in the old password.");

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->withSuccess('Change Password Successfully');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->except('_token', 'password');

        $user = User::create(array_merge($data, [
            "password" => Hash::make($request->password),
            "role_id" => 7 // Set to Member
        ]));

        $user->update([
            'mfc_id_number' => $user->generateNextMfcId(),
        ]);

        $user->assignRole('member');

        event(new Registered($user));

        return back()->withSuccess("User added successfully");
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        if ($request->ajax()) {
            return response()->json([
                "status" => TRUE,
                "user" => $user,
            ]);
        }

        return;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $role = Role::where('id', $request->role_id)->first();

        $user->roles()->detach();
        $user->update($request->except('_method', '_token'));
        $user->assignRole($role);

        return response()->json([
            'status' => TRUE,
            'message' => 'User Updated Successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'status' => TRUE,
            'message' => 'User Deleted Successfully'
        ]);
    }

    public function search(Request $request)
    {
        $users = User::query();
        $mfc_id_number = $request->query('mfc_user_id');

        if ($mfc_id_number) {
            $users = $users->where('mfc_id_number', $mfc_id_number)->get();
        } else {
            $users = [];
        }

        return response()->json([
            'status' => 'success',
            'users' => $users,
        ]);
    }

    public function uploadProfileImage(Request $request, $user_id)
    {
        $user = User::findOrFail($user_id);

        $file = $request->file('profile_image');
        $file_name = $user->mfc_id_number . '.' . $file->getClientOriginalExtension();
        $file_path = "avatars/";

        Storage::disk('public')->putFileAs($file_path, $file, $file_name);

        $user->update([
            'avatar' => $file_name,
        ]);

        return response()->json([
            "status" => TRUE,
            "message" => "Avatar Updated Successfully",
        ]);
    }
}
