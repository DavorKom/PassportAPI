<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Mail\UsersExportMail;
use Barryvdh\DomPDF\Facade as PDF;
use App\Exports\UsersExport;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function mail(Request $request)
    {
        $user = auth()->user();

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

        $unix_timestamp = now()->timestamp;

        $filename = "users-$user->id-$unix_timestamp";

        $pdf = PDF::loadView('users.export-pdf', ['users' => $users]);
        Storage::put("$filename.pdf", $pdf->output());

        $csv = (new UsersExport($users))->store("$filename.xlsx", 'local');

        Mail::to($user->email)->send(new UsersExportMail($filename));

        Storage::delete("$filename.pdf");
        Storage::delete("$filename.xlsx");

        if(Cache::get($user->id)) {
            $value = Cache::get($user->id);
            if($value['export'] < 2) {
                Cache::put($user->id, ['export' => 2, 'time' => $value['time']], $value['time']);
                return $this->out($users);
            } else {
                return $this->outWithErrors([ 'error' => 'Too Many Requests'], 429, 'Too Many Requests');
            }
        }

        Cache::put($user->id, ['export' => 1, 'time' => now()->addMinutes(1) ], now()->addMinutes(1));

        return $this->out($users);
    }

    public function download(Request $request)
    {
        $user = auth()->user();

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

        $unix_timestamp = now()->timestamp;

        $filename = "users-$user->id-$unix_timestamp";

        if($request->input('export') == 'csv') {
            $download = Excel::download(new UsersExport($users), "$filename.xlsx");
        } else {
            $pdf = PDF::loadView('users.export-pdf', ['users' => $users]);
            $download = $pdf->download("$filename.pdf");
        }

        if(Cache::get($user->id)) {
            $value = Cache::get($user->id);
            if($value['export'] < 2) {
                Cache::put($user->id, ['export' => 2, 'time' => $value['time']], $value['time']);
                return $download;
            } else {
                return $this->outWithErrors([ 'error' => 'Too Many Requests'], 429, 'Too Many Requests');
            }
        }

        Cache::put($user->id, ['export' => 1, 'time' => now()->addMinutes(1) ], now()->addMinutes(1));

        return $download;
    }
}