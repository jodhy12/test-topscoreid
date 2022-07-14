@extends('layouts.layout')

@section('title', 'Data Kehadiran')
@section('content')
    <div id="ctrl">
        <div class="container-fluid">
            <div class="col-md-8 mx-auto pt-5">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="d-flex align-items-center justify-content-between">
                                <h3>Data Kehadiran</h3>
                                <div>
                                    <a href="{{ route('addKehadiran') }}" class="btn btn-primary btn-sm">Tambah Data</a>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group mb-1">
                                <p class="p-0 m-1">Data dari <span class="font-weight-bold">@{{ datas.from }}</span>
                                    sampai
                                    <span class="font-weight-bold">@{{ datas.to }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Filter by</label>
                            <select name="total" class="form-control" style="width: 30%" @change=handleChange>
                                <option value="" selected>Select Filter</option>
                                <option value="0">Tampilkan semua</option>
                                <option value="4">Total absen lebih dari 3</option>
                            </select>
                        </div>
                        <table class="table table-bordered m-0">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width:100px">No</th>
                                    <th class="text-center">Data Karyawan</th>
                                    <th class="text-center">Total Absen</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(data,index) in datas.data" :class="{ 'bg-danger': data.count > 3 }">
                                    <td class="text-center">@{{ parseInt(index) + 1 }}</td>
                                    <td class="text-center">@{{ data.karyawan }}</td>
                                    <td class="text-center">@{{ data.count }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer clearfix">
                        <ul class="pagination pagination-sm m-0 float-right">
                            <li class="page-item"><a class="page-link"
                                    :href="datas.prev_page_url ? datas.prev_page_url.substring(1) : datas.prev_page_url">&laquo;</a>
                            </li>
                            <li v-if="datas.current_page !== 1 && datas.last_page > 1" class="page-item"><a
                                    class="page-link" :href="datas.first_page_url.substring(1)">First</a>
                            </li>
                            <li class="page-item" :class="{ active: datas.current_page == index }"
                                v-for="index in datas.last_page"><a class="page-link"
                                    :href="'?page=' + index">@{{ index }}</a>
                            </li>
                            <li v-if="datas.last_page !== datas.current_page && datas.last_page > 1" class="page-item"><a
                                    class="page-link" :href="datas.last_page_url.substring(1)">Last</a>
                            </li>
                            <li class="page-item"><a class="page-link"
                                    :href="datas.next_page_url ? datas.next_page_url.substring(1) : datas.next_page_url">&raquo;</a>
                            </li>
                        </ul>
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
                    datas: {!! json_encode($absens) !!},
                    filter: 0,
                    url: '{{ route('kehadiran') }}',
                }
            },
            mounted() {},
            methods: {
                handleChange() {
                    this.filter = $('select[name=total]').val()
                    window.location.href = this.url + '?total=' + this.filter
                }
            }
        }).mount('#ctrl')
    </script>
@endsection
