@extends('layouts.appAdmin')

@section('content')
@include('components.navbarAdmin')
<div class="main-content">
  <h1 class="poppins-bold text-black mt-3" style="font-size: 22px">Tambah Daftar Perusahaan</h1>
  <div class="box-form">
      <form action="">
        <label for="namaPerusahaan" class="poppins-bold text-black mb-2">Nama Perusahaan:</label>
        <input type="text" class="form-control mb-3" id="namaPerusahaan" placeholder="Masukkan nama Perusahaan">

        <label for="alamatPerusahaan" class="poppins-bold text-black mb-2">Alamat Perusahaan:</label>
        <input type="text" class="form-control mb-3" id="alamatPerusahaan" placeholder="Pilih Kota/Kabupaten Perusahaan">

        <label for="websitePerusahaan" class="poppins-bold text-black mb-2">Website Perusahaan:</label>
        <input type="text" class="form-control mb-3" id="websitePerusahaan" placeholder="Masukkan *link website Perusahaan">

        <label for="deskripsi" class="form-label poppins-bold text-black mb-2">Deskripsi Perusahaan:</label>
        <textarea class="form-control mb-3" id="deskripsi" placeholder="Masukkan deskripsi Perusahaan"></textarea>  

        <label for="myFile" class="form-label poppins-bold text-black mt-2">Tambahkan Gambar/Logo:</label>
        <div class="button-wrap">
          <label class="buttonUploadFile" for="upload">
            <i class='bx bx-upload me-1'></i>
            Choose a File
          </label>
          <input id="upload" type="file">
        </div>
        <div class="d-flex justify-content-end align-items-end gap-2">
          <button>Batal</button>
          <button>Tambah</button>
        </div>
      </form>
  </div>
</div>
@endsection
