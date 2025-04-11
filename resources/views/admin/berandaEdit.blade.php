@extends('layouts.appAdmin')

@section('content')
    @include('components.navbarAdmin')
    <div class="main-content">
        <h1 class="poppins-bold text-black mt-3" style="font-size: 22px">Edit Beranda</h1>
        <div class="box-form">
            <form action="{{ route('beranda.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="deskripsi_beranda">Deskripsi Beranda</label>
                    <textarea class="form-control" id="deskripsi_beranda" name="deskripsi_beranda" rows="10" required>{{ old('deskripsi_beranda', $beranda->deskripsi_beranda) }}</textarea>
                </div>


                <div class="d-flex justify-content-end align-items-end gap-2 mt-3">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>

            </form>
        </div>
    </div>
@endsection
