@extends('layouts.app')

@section('title', 'Tracer Study Report')

@section('content')
<section class="report-section">
  <div class="container">
    <h2>Tracer Study Report</h2>
    <p>Pilih Fakultas, Prodi dan Tahun Angkatan untuk melihat hasil tracer study.</p>
    <div>
        <form id="formFilter">
            @csrf
            <div class="form-group">
                <label for="fakultas">Fakultas</label>
                <select id="fakultas" name="fakultas" class="form-control">
                    <option value="">Pilih Fakultas</option>
                    @foreach($fakultas as $f)
                    <option value="{{$f->id}}">{{$f->name}}</option>
                    @endforeach
                    </select>
            </div>
            <div class="form-group">
                <label for="prodi">Prodi</label>
                <select id="prodi" name="prodi" class="form-control">
                    <option value="">Pilih Prodi</option>
                </select>
            </div>
            <div class="form-group">
                <label for="tahun_angkatan">Tahun Angkatan</label>
                <select id="tahun_angkatan" name="tahun_angkatan" class="form-control">
                    <option value="">Pilih Tahun Angkatan</option>
                </select>
            </div>
        </form>
    </div>
    <div class="year-cards">
    <div class="year-card" id="data">
        <p>No Data</p>
    </div>
    </div>
</div>
</section>
<script>
    $(document).ready(function () {
        $('#fakultas').change(function (e) {
            e.preventDefault();
            var fakultas_id = $(this).val();
            $.ajax({
                type: 'GET',
                url: '{{ url('/get-prodi') }}',
                data: {fakultas_id: fakultas_id},
                success: function (data) {
                    $('#prodi').empty();
                    $('#angkatan').empty();
                    $('#prodi').append('<option value="">Pilih Prodi</option>');
                    $.each(data, function (key, value) {
                        $('#prodi').append('<option value="'+value.id+'">'+value.name+'</option>');
                    });
                    searchData();
                }
            });
        });

        $('#prodi').change(function (e) {
            e.preventDefault();
            var prodi_id = $(this).val();
            $.ajax({
                type: 'GET',
                url: '{{ url('/get-tahun-angkatan') }}',
                data: {prodi_id: prodi_id},
                success: function (data) {
                    $('#tahun_angkatan').empty();
                    $('#tahun_angkatan').append('<option value="">Pilih Tahun Angkatan</option>');
                    $.each(data, function (key, value) {
                        $('#tahun_angkatan').append('<option value="'+value+'">'+value+'</option>');
                    });
                    searchData();
                }
            });
        });

        $('#tahun_angkatan').change(function (e) {
            e.preventDefault();
            searchData();
        });

        function searchData() {
            var fakultas_id = $('#fakultas').val();
            var prodi_id = $('#prodi').val();
            var angkatan = $('#tahun_angkatan').val();

            if (fakultas_id == '') {
                $('#data').html('<p>No Data</p>');
                return;
            }

            $.ajax({
                type: "GET",
                url: '{{ url('/get-report') }}',
                data: {
                    fakultas_id: fakultas_id,
                    prodi_id: prodi_id,
                    angkatan: angkatan
                },
                success: function (response) {
                    if (response.status == 'success') {
                        $('#data').html(response.html);
                    } else {
                        toastr.error(response.message);
                    }
                }
            });
        }
    });
</script>
@endsection
