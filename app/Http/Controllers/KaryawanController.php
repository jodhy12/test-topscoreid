<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class KaryawanController extends Controller
{
    public function index()
    {
        $karyawan = $this->getUsers();
        return view('admin.karyawan', compact('karyawan'));
    }

    public function kehadiran(Request $request)
    {
        $dataPerPage = 5;
        $absens = $this->dataUsers(intVal($request->query('total')));
        $absens = $this->paginator($absens, $dataPerPage, $request->page);
        return view('admin.kehadiran.kehadiran', compact('absens'));
    }

    public function addKehadiran()
    {
        $users = $this->getUsers();
        return view('admin.kehadiran.addKehadiran', compact('users'));
    }

    public function store(Request $request)
    {
        Http::post('https://ayohadir.id/v1/input_absen', [
            'user_id' => $request->user_id,
        ]);
    }

    public function paginator($data, $perPage = 5, $currentPage = null, $options = [])
    {
        $perPage = $perPage ?: (Paginator::resolveCurrentPage() ?: 1);
        $data = $data instanceof Collection ? $data : Collection::make($data);
        return new LengthAwarePaginator($data->forPage($currentPage, $perPage), $data->count(), $perPage, $currentPage, $options);
    }

    public function getAbsen()
    {
        $response = Http::get('https://ayohadir.id/v1/absen');
        $absens = $response->object();
        return $absens->results;
    }

    public function getUsers()
    {
        $response = Http::get('https://ayohadir.id/v1/karyawan');
        $users = $response->object();
        return $users->results;
    }

    public function dataAbsen()
    {
        $absensRaw = $this->getAbsen();
        return $absensRaw;
    }

    public function dataUsers($params)
    {

        $absensRaw = $this->getAbsen();
        $usersRaw = $this->getUsers();
        foreach ($usersRaw as $user) {
            $data = [];
            foreach ($absensRaw as $absen) {
                if ($absen->user_id == $user->user_id) {
                    array_push($data, $absen);
                }
            }
            $user->absens = $data;
        }

        $users = [];
        foreach ($usersRaw as $user) {
            if ($user->absens) {
                array_push($users, $user);
            }
        }

        foreach ($users as $user) {
            $user->count = count($user->absens);
        }
        if ($params > 3) {
            $userCount = [];
            foreach ($users as $user) {
                if ($user->count > 3) {
                    array_push($userCount, $user);
                }
            }
            return $userCount;
        }

        return $users;
    }
}
