<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class KaryawanController extends Controller
{

    // GET Home Page
    public function index()
    {
        $karyawan = $this->getUsers();
        return view('admin.karyawan', compact('karyawan'));
    }

    // GET Absen Page
    public function kehadiran(Request $request)
    {
        // Get endpoint query
        $filter = $request->query('total');
        $dataPerPage = $request->query('perPage');

        // Validate
        $dataPerPage = $dataPerPage != 5 && $dataPerPage != 10 && $dataPerPage != 15 ? 5 : $dataPerPage;
        $filter = $filter !== 'absen' ? 'all' : 'absen';

        // Process Data
        $absens = $this->dataUsers($filter);
        $absens = $this->paginator($absens, $dataPerPage, $request->page);
        return view('admin.kehadiran.kehadiran', compact('absens', 'filter', 'dataPerPage'));
    }

    // GET Create Absen Page
    public function addKehadiran()
    {
        $users = $this->getUsers();
        return view('admin.kehadiran.addKehadiran', compact('users'));
    }

    // POST Store Absen
    public function store(Request $request)
    {
        Http::post('https://ayohadir.id/v1/input_absen', [
            'user_id' => $request->user_id,
        ]);
    }

    // Paginate Function
    public function paginator($data, $perPage = 5, $currentPage = null, $options = [])
    {
        $perPage = $perPage ?: (Paginator::resolveCurrentPage() ?: 1);
        $data = $data instanceof Collection ? $data : Collection::make($data);
        return new LengthAwarePaginator($data->forPage($currentPage, $perPage), $data->count(), $perPage, $currentPage, $options);
    }

    // GET Api Absen
    public function getAbsen()
    {
        $response = Http::get('https://ayohadir.id/v1/absen');
        $absens = $response->object();
        return $absens->results;
    }

    //GET Api Karyawan
    public function getUsers()
    {
        $response = Http::get('https://ayohadir.id/v1/karyawan');
        $users = $response->object();
        return $users->results;
    }

    //GET function all data absen
    public function dataAbsen()
    {
        $absensRaw = $this->getAbsen();
        return $absensRaw;
    }

    //GET function all data user with absen relation
    //the @params is for the filter data
    public function dataUsers($params)
    {

        $absensRaw = $this->getAbsen();
        $usersRaw = $this->getUsers();
        $users = [];
        foreach ($usersRaw as $user) {
            $data = [];
            foreach ($absensRaw as $absen) {
                if ($absen->user_id == $user->user_id) {
                    array_push($data, $absen);
                }
            }
            $user->absens = $data;
            if ($user->absens) {
                $user->count = count($user->absens);
                array_push($users, $user);
            }
        }

        if ($params === 'absen') {
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
