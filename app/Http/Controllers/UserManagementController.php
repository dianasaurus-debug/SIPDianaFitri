<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Http\Resources\UserResource;
use App\Imports\UsersImport;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class UserManagementController extends Controller
{
//    /**
//     * Display a listing of the resource.
//     *
//     * @return \Illuminate\Http\Response
//     */
    public function index()
    {
//        $users = User::all();
        $users = User::withFilters(
            request()->input('role'),
            request()->input('created_at'),
        )->get();
//        return UserResource::collection($users)->paginate(10)->toJson();
        return UserResource::collection($users)->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:3|max:255',
            'email' => 'required|email|unique:users|max:255',
            'username' => 'required|unique:users|max:255',
            'password' => 'required|min:6',
            'nomor_hp' => 'required|numeric',
            'role_id' => 'required|numeric',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'nomor_hp' => $request->nomor_hp,
            'role_id' => $request->role_id,
        ]);
        return ['message' => 'User Created!'];//
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        UserResource::withoutWrapping();

        return new UserResource($user);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $this->validate($request, [
            'name' => 'required|min:3|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
            'username' => 'required|unique:users,username,'.$user->id,
            'password' => 'required|min:6',
            'nomor_hp' => 'required|numeric',
            'role_id' => 'required|numeric',
        ]);
        $user->update($request->all());
        return ['Message' => 'User has successfully updated!'];
    }


    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return ['Message' => 'User has successfully deleted!'];
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new UsersExport($request->role, $request->created_at), 'users.xlsx');
    }
    public function importExcel(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file|mimes:xls,xlsx'
        ]);

        $path = $request->file('import_file');
        $data = Excel::import(new UsersImport, $path);

        return response()->json(['message' => 'uploaded successfully'], 200);
    }
    public function multipleDestroy($id)
    {
        $single_user_id = explode(',' , $id);
        $ids = [];
        foreach($single_user_id as $id) {
            User::findOrFail($id)->delete();
        }

    }
}
