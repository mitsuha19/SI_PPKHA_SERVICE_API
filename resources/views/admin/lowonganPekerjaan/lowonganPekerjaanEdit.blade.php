@extends('layouts.appAdmin')

@section('content')
@include('components.navbarAdmin')
<div class="main-content">
  <h1 class="poppins-bold text-black mt-3" style="font-size: 22px">Edit Lowongan Pekerjaan</h1>
  <div class="box-form">
  <form action="{{ route('lowongan.update', $lowongan->id) }}" method="POST" enctype="multipart/form-data">
      @csrf 
      @method('PUT')

        <label for="judulLowongan" class="poppins-bold text-black mb-2">Judul Lowongan:</label>
        <input type="text" class="form-control mb-3" id="judulLowongan" name="judulLowongan" value="{{ old('judulLowongan', $lowongan->judulLowongan) }}" required>

        <label for="jenisLowongan" class="poppins-bold text-black mb-2">Jenis Lowongan:</label>
        <input type="text" class="form-control mb-3" id="jenisLowongan" name="jenisLowongan" value="{{ old('jenisLowongan', $lowongan->jenisLowongan) }}" required>

        <label for="tipeLowongan" class="poppins-bold text-black mb-2">Tipe Lowongan:</label>
        <select name="tipeLowongan" class="form-select" required>
                      <option value="Full-time" {{ $lowongan->tipeLowongan == 'Full-time' ? 'selected' : '' }}>Full-time</option>
                      <option value="Part-time" {{ $lowongan->tipeLowongan == 'Part-time' ? 'selected' : '' }}>Part-time</option>
                      <option value="Magang" {{ $lowongan->tipeLowongan == 'Magang' ? 'selected' : '' }}>Magang</option>
                      <option value="Kontrak" {{ $lowongan->tipeLowongan == 'Kontrak' ? 'selected' : '' }}>Kontrak</option>
        </select>

        <label for="deskripsiLowongan" class="form-label poppins-bold text-black mb-2">Deskripsi Lowongan:</label>
        <textarea class="form-control mb-3" id="deskripsiLowongan" name="deskripsiLowongan" placeholder="Masukkan Deskripsi Lowongan" required>{{ old('deskripsiLowongan', $lowongan->deskripsiLowongan) }}</textarea>

        <label for="kualifikasi" class="form-label poppins-bold text-black mb-2">Kualifikasi Lowongan:</label>
        <textarea class="form-control mb-3" id="kualifikasi" name="kualifikasi" placeholder="Masukkan Kualifikasi Lowongan" required>{{ old('kualifikasi', $lowongan->kualifikasi) }}</textarea>  

        <label for="benefit" class="form-label poppins-bold text-black mb-2">Benefit Lowongan:</label>
        <textarea class="form-control mb-3" id="benefit" name="benefit" placeholder="Masukkan Benefit Lowongan" required>{{ old('benefit', $lowongan->benefit) }}</textarea>  

        <label for="keahlian" class="poppins-bold text-black mb-2">Tambahkan Keahlian Lowongan:</label>
        <div id="keahlianContainer">
    @php
        $keahlianArray = old('keahlian', isset($lowongan->keahlian) ? explode(',', $lowongan->keahlian) : []);
    @endphp

    @foreach($keahlianArray as $keahlian)
    <div class="keahlian-item d-flex mb-2">
        <input type="text" class="form-control me-2" name="keahlian[]" value="{{ trim($keahlian) }}" required>
        <button type="button" class="btn btn-danger btn-sm" onclick="removeKeahlian(this)">❌</button>
    </div>
    @endforeach
    <button type="button" class="btn mt-1 mb-3" style="width: fit-content; height: fit-content;" onclick="addKeahlian()">
      <i class='bx bx-plus'></i>
      Tambah Keahlian
    </button> 

</div>


    <label for="batasMulai" class="poppins-bold text-black mb-2">Batas Mulai Lamaran:</label>
<input type="date" class="form-control mb-3" id="batasMulai" name="batasMulai" 
    value="{{ old('batasMulai', isset($lowongan->batasMulai) ? date('Y-m-d', strtotime($lowongan->batasMulai)) : '') }}" required>

<label for="batasAkhir" class="poppins-bold text-black mb-2">Batas Akhir Lamaran:</label>
<input type="date" class="form-control mb-3" id="batasAkhir" name="batasAkhir" 
    value="{{ old('batasAkhir', isset($lowongan->batasAkhir) ? date('Y-m-d', strtotime($lowongan->batasAkhir)) : '') }}" required>

   

        
        
        <div class="d-flex justify-content-end align-items-end gap-2">
        <button type="submit" class="btn" style="background-color: #13C56B; color: white; border: none;">
          Edit
        </button>
        <a href="{{ route('admin.lowonganPekerjaan.lowonganPekerjaan') }}" class="btn btn-secondary">Batal</a>
          
        </div>
      </form>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    let keahlianInput = document.getElementById("keahlian");
    keahlianInput.addEventListener("input", function() {
        this.value = this.value.replace(/[^a-zA-Z0-9, ]/g, '');
    });
});
</script>

<script>
 function addKeahlian() {
    let container = document.getElementById('keahlianContainer');
    let newInput = document.createElement('div');
    newInput.classList.add('keahlian-item', 'd-flex', 'mb-2');
    newInput.innerHTML = `
        <input type="text" class="form-control me-2" name="keahlian[]" placeholder="Masukkan Keahlian" required>
        <button type="button" class="btn btn-danger btn-sm" onclick="removeKeahlian(this)">❌</button>
    `;
    container.appendChild(newInput);
}

function removeKeahlian(button) {
    let container = document.getElementById('keahlianContainer');
    if (container.children.length > 1) { // Pastikan minimal satu input tetap ada
        button.parentElement.remove();
    }
}
</script>


@endsection
