@extends('layouts.layout')

@section('title', 'Input Kehadiran')
@section('content')
    <div id="ctrl">
        <div class="container-fluid">
            <div class="col-md-8 mx-auto pt-5">
                <div class="card">
                    <div class="card-header">
                        <h3>Input Data Kehadiran</h3>
                    </div>
                    <div class="alert alert-success" v-if="message">
                        @{{ message }}
                    </div>
                    <div class="card-body">
                        <form :action="url" method="POST" @submit.prevent="postData">
                            <div class="form-group">
                                <label>Input Nama Karyawan</label>
                                <select class="form-control select2" style="width: 100%" name="user_id">
                                    @foreach ($users as $user)
                                        <option value="{{ $user->user_id }}">{{ $user->karyawan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="row">
                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('JS')
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        const {
            createApp
        } = Vue
        const app = createApp({
            data() {
                return {
                    message: null,
                    url: '{!! route('kehadiran') !!}'
                }
            },
            mounted() {
                $('.select2').select2()
            },
            methods: {
                postData() {
                    let select = $('select[name=user_id]').val()
                    let data = {
                        _token: '{{ csrf_token() }}',
                        user_id: parseInt(select)
                    }

                    fetch(this.url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(data)
                        }).then(res => {
                            this.message = 'Data berhasil dibuat';
                            window.location.href = '{{ route('kehadiran') }}';
                        })
                        .catch(err => console.log(err))
                }
            },
        }).mount('#ctrl')
    </script>
@endsection
