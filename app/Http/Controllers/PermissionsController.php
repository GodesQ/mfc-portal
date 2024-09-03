<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\DataTables;

class PermissionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {   
        $endPoint = "Permissions";

        if($request->ajax()) {
            $permissions = Permission::query();

           return DataTables::of($permissions)
           ->editColumn('created_at', function ($permission) {
                return Carbon::parse($permission->created_at)->format('F d, Y');
           })
            ->addColumn('actions', function ($permission) {
                $actions = "<div class='hstack gap-2'>
                    <a href='" . route('permissions.show', ['permission' => $permission->id]) . "' target='_blank' class='btn btn-soft-primary btn-sm' data-bs-toggle='tooltip' data-bs-placement='top' title='View'><i class='ri-eye-fill align-bottom'></i></a>
                    <a href='" . route('permissions.edit', ['permission' => $permission->id]) . "' class='btn btn-soft-success btn-sm' data-bs-toggle='tooltip' data-bs-placement='top' title='Edit'><i class='ri-pencil-fill align-bottom'></i></a>
                    <button type='button' class='btn btn-soft-danger btn-sm remove-btn' id='" . $permission->id . "' data-bs-toggle='tooltip' data-bs-placement='top' title='Remove'><i class='ri-delete-bin-5-fill align-bottom'></i></button>
                </div>";

                return $actions;
            })
            ->rawColumns(['actions'])
            ->make();
        }

        return view('pages.permissions.list', compact('endPoint'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        
    }
}
