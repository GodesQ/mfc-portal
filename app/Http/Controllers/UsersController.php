<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\StoreRequest;
use App\Models\Section;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\UserMissionaryService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, string $section)
    {
        //
        $users = '';
        $btn_color = '';
        $breadcrumb = $section;
        switch ($section) {
            case 'kids':
                $users = User::where('section_id', 1)->get();
                $btn_color = 'btn-danger';
                break;
            case 'youth':
                $users = User::where('section_id', 2)->get();
                $btn_color = 'btn-primary';
                break;
            case 'singles':
                $users = User::where('section_id', 3)->get();
                $btn_color = 'btn-success';
                break;
            case 'handmaids':
                $users = User::where('section_id', 4)->get();
                $btn_color = 'btn-red';
                break;
            case 'servants':
                $users = User::where('section_id', 5)->get();
                $btn_color = 'btn-warning';
                break;
            default:
                $users = User::where('section_id', 6)->get();
                $btn_color = 'btn-info';
                break;
        }

        if ($request->ajax()) {
            return DataTables::of($users)
                ->addColumn('actions', function ($user) {
                    $actions = "<div class='hstack gap-2'>
                        <button type='button' class='btn btn-soft-success btn-sm edit-btn' id='" . $user->id . "' data-bs-toggle='tooltip' data-bs-placement='top' title='Edit'><i class='ri-pencil-fill align-bottom'></i></button>
                        <button type='button' class='btn btn-soft-danger btn-sm remove-btn' id='" . $user->id . "' data-bs-toggle='tooltip' data-bs-placement='top' title='Remove'><i class='ri-delete-bin-fill align-bottom'></i></button>
                    </div>";

                    return $actions;
                })
                ->addColumn('name', function ($user) {
                    $name = $user->first_name . ' ' . $user->last_name;

                    return $name;
                })
                ->rawColumns(['actions', 'name'])
                ->make(true);
        }

        $sections = Section::get();
        $roles = Role::get();
        return view('pages.users.index', compact('section', 'breadcrumb', 'btn_color', 'sections', 'roles'));
    }

    public function profile(string $id)
    {   
        $sections = Section::get();
        $user = User::findOrFail($id);

        $user->load('missionary_services');
        

        return view('pages.profile.index', compact('user', 'sections'));
    }

    public function updateProfile(Request $request, string $id) {
        $user_data = $request->except('_token', '_method', 'god_given_skills');
        $user = User::where('id', $id)->with('user_details')->firstOrFail();

        $user->update(array_merge($user_data));

        $skills = [];

        if($request->has('god_given_skills') && is_array($request->god_given_skills)) {
            foreach ($request->god_given_skills as $key => $skill) {
                array_push($skills, $skill);
            }
        }

        if($user->user_details) {
            $user->user_details->update(array_merge($user_data, ['god_given_skill' => $skills]));
        } else {
            UserDetail::create(array_merge($user_data, ['user_id' => $user->id, 'god_given_skill' => $skills]));
        }
        
        return back()->withSuccess("Profile Updated Successfully");
    }

    public function updateProfileService(Request $request, $id) {
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

    public function updatePassword(Request $request, $id) {
        $user = User::findOrFail($id);

        if(!Hash::check($request->old_password, $user->password)) return back()->with('fail', "The user password is not match in the old password.");

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

        if($request->ajax()) {
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
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function search(Request $request) {
        $users = User::query();

        if($request->query('mfc_user_id')) {
            $users = $users->where('mfc_id_number', $request->query('mfc_user_id'));
        }

        $users = $users->get();

        return response()->json([
            'status' => 'success',
            'users' => $users,
        ]);
    }
}
