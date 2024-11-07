<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class userController extends Controller
{
    public function admin()
    {
        $data = User::all();
        return view('index', compact('data'));
    }

    public function adduser(Request $request)
    {
        user::create($request->all());
        return response()->json([
            'status' => 200,
            'message' => 'user added sucessfully'
        ]);
    }
    public function deleteuser($id)
    {
        $user = user::findorFail(id: $id);
        $user->delete();

        return response()->json(data: [
            'status' => 500,
            'message' => 'user deleted sucessfully'
        ]);
    }
    public function edituser($id)
    {
        $user = user::findOrFail(id: $id);
        return response()->json(data:[
            'status' => 200,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'dept' => $user->dept
            ]
        ]);
    }
    public function updateuser(Request $request, $id){
        $user = user::findorFail($id);
        $user->update($request->all());

        return response()->json([
            'status' => 200,
            'message' => 'User Updated successfully!'
        ]);
    }
}
