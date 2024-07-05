<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

    public function get(Request $request)
    {
        $users = $this->getUsers();

        return response()->json([
            "status" => "success",
            "users" => $users
        ]);
    }

    public function show(Request $request, $id)
    {
        $user = User::find($id);

        return response()->json([
            "status" => "success",
            "user" => $user
        ]);
    }

    public function getLeaders()
    {
        $leaders = User::where([
            "role" => 'Team Leader'
        ])->get();

        return response()->json([
            "status" => "success",
            "leaders" => $leaders
        ]);
    }

    public function getAgents(Request $request)
    {
        $user = $request->user();

        $leaders = User::where([
            "role" => 'Agent',
            "team_leader" => $user->id
        ])->get();

        return response()->json([
            "status" => "success",
            "leaders" => $leaders
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'role' =>  'required',
            'status' => 'required',
        ]);

        if ($request->role == 'Agent') {
            $request->validate([
                'team_leader' => 'required|integer',
            ]);
        }

        $user = new User([
            'username'  => $request->name,
            'name'  => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'status' => $request->status,
            'team_leader' => $request->team_leader,
            'caller_id' => $request->caller_id,
            'contact_number' => $request->contact_number,
            'password' => bcrypt($request->password),
        ]);

        if ($user->save()) {
            return response()->json([
                'message' => 'success'
            ], 201);
        } else {
            return response()->json(['error' => 'Provide proper details']);
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'username' => 'required|string',
            'role' =>  'required',
            'status' => 'required',
        ]);

        if ($request->role == 'Agent') {
            $request->validate([
                'team_leader' => 'required|integer',
            ]);
        }

        $user = User::find($request->id);

        $user->username  = $request->name;
        $user->name  = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->status = $request->status;
        $user->team_leader = $request->team_leader;
        $user->caller_id = $request->caller_id;
        $user->contact_number = $request->contact_number;
        $user->password = bcrypt($request->password);

        if ($user->save()) {
            return response()->json([
                'message' => 'success'
            ], 201);
        } else {
            return response()->json(['error' => 'Provide proper details']);
        }
    }

    public function activate(Request $request, $id)
    {
        $user = User::find($id);
        $user->status = $user->status == 'active' ?
            'inactive' :
            'active';

        $user->save();

        $users = $this->getUsers();

        return response()->json([
            "status" => "success",
            "users" => $users
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $user = User::find($id);
        $user->delete();

        $users = $this->getUsers();

        return response()->json([
            "status" => "success",
            "users" => $users
        ]);
    }


    private function getUsers()
    {
        $me = Auth::user();
        $filter = [];
        if ($me->role != 'Admin')
            $filter = [
                'role' => 'Agent'
            ];

        $users = User::where($filter)->get();

        return $users;
    }
}
