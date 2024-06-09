@extends('layouts.layout')

<style>
    .nav-tabs .nav-item .nav-link {
        background-color: #ffffff;
        color: #000000;
    }

    .nav-tabs .nav-item .nav-link.active {
        background-color: #007bff;
        color: #ffffff;
    }
</style>

@section('main')
<section class="blog-banner-area" id="category">
    <div class="container h-100">
        <div class="blog-banner">
            <div class="text-center">
                <h1>Profile</h1>
                <nav aria-label="breadcrumb" class="banner-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('front.index') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Profile</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>
<div class="container mt-5 mb-5">
    <div class="row justify-content-center card shadow">
        <div class="col-md-12">
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Ubah Profile</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile"
                                 aria-selected="false">Ubah Password</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Formulir Profil -->
                    <div>
                        <form id="profileForm" method="POST" action="{{ route('costumer.updateProfile') }}">
                            @csrf
                            @method('PUT')
                            <div class="col-md-12 form-group">
                                <input type="text" class="form-control" id="name" name="name" placeholder="Your Name" value="{{ $customer->name }}" required autocomplete="name">
                            </div>
                            
                            <div class="col-md-12 form-group">
                                <input type="email" class="form-control" id="email" name="email" placeholder="Email Address" value="{{ $customer->email }}" required>
                            </div>
                            
                            <div class="col-md-12 form-group">
                                <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="Your Phone Number" value="{{ $customer->phone_number }}" required autocomplete="phone_number">
                            </div>
                            
                            <div class="col-md-12 form-group">
                                <input type="text" class="form-control" id="address" name="address" placeholder="Your Address" value="{{ $customer->address }}" required autocomplete="address">
                            </div>
                            
                            <div class="col-md-12 form-group p_star">
                                <label for="">Provinsi</label>
                                <select class="form-control" id="province_id" name="province_id" required>
                                    <option value="">Pilih Provinsi</option>
                                    @foreach ($provinces as $row)
                                        <option value="{{ $row->id }}" {{ $row->id == $customer->province_id ? 'selected' : '' }}>{{ $row->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-12 form-group p_star">
                                <label for="">Kabupaten / Kota</label>
                                <select class="form-control" name="citie_id" id="city_id" required>
                                    <option value="">Pilih Kabupaten/Kota</option>
                                    @foreach ($cities as $row)
                                        <option value="{{ $row->id }}" {{ $row->id == $customer->citie_id ? 'selected' : '' }}>{{ $row->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-12 form-group p_star">
                                <label for="">Kecamatan</label>
                                <select class="form-control" name="district_id" id="district_id" required>
                                    <option value="">Pilih Kecamatan</option>
                                    @foreach ($districts as $row)
                                        <option value="{{ $row->id }}" {{ $row->id == $customer->district_id ? 'selected' : '' }}>{{ $row->name }}</option>
                                    @endforeach
                                </select>
                            </div>                                                                                            
    
                            <button type="submit" class="btn btn-primary">Simpan Perubahan Profil</button>
                        </form>
                    </div>

                    <!-- Formulir Kata Sandi -->
                    <div>
                        <form id="passwordForm" method="POST" action="{{ route('costumer.updatePassword') }}" class="d-none">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="current_password">Kata Sandi Saat Ini:</label>
                                <input type="password" id="current_password" name="current_password" class="form-control" required>
                            </div>
    
                            <div class="form-group">
                                <label for="new_password">Kata Sandi Baru:</label>
                                <input type="password" id="new_password" name="new_password" class="form-control">
                            </div>
    
                            <div class="form-group">
                                <label for="new_password_confirmation">Konfirmasi Kata Sandi Baru:</label>
                                <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-control">
                            </div>
    
                            <button type="submit" class="btn btn-primary">Simpan Perubahan Kata Sandi</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')

    <script>
        $(document).ready(function () {
            $('#myTab a').on('click', function (e) {
                e.preventDefault();
                $(this).tab('show');

                // Toggle the visibility of forms based on the selected tab
                if ($(this).attr('id') === 'home-tab') {
                    $('#profileForm').removeClass('d-none');
                    $('#passwordForm').addClass('d-none');
                } else if ($(this).attr('id') === 'profile-tab') {
                    $('#profileForm').addClass('d-none');
                    $('#passwordForm').removeClass('d-none');
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@latest"></script>
    <script>
        // Jika terdapat pesan sukses
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 2500 // Tampilkan selama 1.5 detik, sesuaikan dengan kebutuhan Anda
            });
        @endif
    </script>

    <script type="text/javascript">
        $('#province_id').on('change', function() {
            $.ajax({
                url: "{{ url('/api/city') }}",
                type: "GET",
                data: { province_id: $(this).val() },
                success: function(html){

                    $('#city_id').empty()
                    $('#city_id').append('<option value="">Pilih Kabupaten/Kota</option>')
                    $.each(html.data, function(key, item) {
                        $('#city_id').append('<option value="'+item.id+'">'+item.name+'</option>')
                    })
                }
            });
        })

        $('#city_id').on('change', function() {
            $.ajax({
                url: "{{ url('/api/district') }}",
                type: "GET",
                data: { city_id: $(this).val() },
                success: function(html){
                    $('#district_id').empty()
                    $('#district_id').append('<option value="">Pilih Kecamatan</option>')
                    $.each(html.data, function(key, item) {
                        $('#district_id').append('<option value="'+item.id+'">'+item.name+'</option>')
                    })
                }
            });
        })
    </script>

@endsection