@extends('layouts.layout')

@section('title', 'Data Karyawan')
@section('content')
    <div id="ctrl">
        <div class="container-fluid">
            <div class="col-md-8 mx-auto pt-5">
                <div class="card">
                    <div class="card-header">
                        <h3>Data Karyawan</h3>
                    </div>
                    <div class="card-body">
                        <table id="datatable" class="table table-bordered m-0" v-if="datas.length">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width:100px">No</th>
                                    <th class="text-center">Data Karyawan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(data,index) in datas">
                                    <td class="text-center">@{{ index + 1 }}</td>
                                    <td class="text-center">@{{ data.karyawan }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('JS')
    <script>
        const {
            createApp
        } = Vue
        const app = createApp({
            data() {
                return {
                    datas: {!! json_encode($karyawan) !!},
                }
            },
            mounted() {
                $('#datatable').DataTable()
            },
            methods: {},
        }).mount('#ctrl')
    </script>
@endsection
