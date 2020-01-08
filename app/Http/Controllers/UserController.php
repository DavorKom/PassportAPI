<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function export(Request $request)
    {
        $query = User::query();

        if($request->filled('name')) {
            $query->where('name', 'LIKE', '%'. $request->name .'%');
        }

        if($request->filled('contract_start_date')) {
            $query->where('contract_start_date', $request->input('contract_start_date'));
        }

        if($request->filled('contract_end_date')) {
            $query->where('contract_end_date', $request->input('contract_end_date'));
        }

        if($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        if($request->filled('verified')) {
            $query->where('verified', '==', $request->input('type'));
        }

        $users = $query->get();
        $users = UserResource::collection($users)->toArray($request);

        return $this->out($users);
    }
}
