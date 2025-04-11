@extends('layouts.appAdmin')

@section('content')
@include('components.navbarAdmin')
<div class="main-content" style="max-height: 80vh; overflow-y: auto; padding: 15px;">
  <h1 class="poppins-bold text-black mt-3" style="font-size: 22px">Tambah Lowongan Pekerjaan</h1>
  <div class="box-form">
      <form action="{{ route('admin.lowonganPekerjaan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="judulLowongan" class="poppins-bold text-black mb-2">Judul Lowongan:</label>
        <input type="text" class="form-control mb-3" id="judulLowongan" name="judulLowongan" placeholder="Masukkan Judul Lowongan" required>

        <label for="namaPerusahaan" class="poppins-bold text-black mb-2">Nama Perusahaan:</label>
        <select id="namaPerusahaan" name="namaPerusahaan" class="form-control mb-3" onchange="toggleOtherInput()" required>
          <option value="">Pilih Nama Perusahaan</option>
          @foreach($perusahaan as $p)
             <option value="{{ $p->namaPerusahaan }}">{{ $p->namaPerusahaan }}</option>
         @endforeach
       
          <option value="Other">Other</option>
        </select>
        
        <!-- Input tambahan jika memilih "Other" -->
        <div id="otherInputContainer" style="display: none;">
          <label for="namaPerusahaanBaru" class="poppins-bold text-black mb-2">Nama Perusahaan Baru:</label>
          <input type="text" class="form-control mb-3" id="namaPerusahaanBaru" name="namaPerusahaanBaru" placeholder="Masukkan Nama Perusahaan Baru">

          <label for="lokasiPerusahaan" class="poppins-bold text-black mb-2">Lokasi Perusahaan:</label>
          <input type="text" class="form-control mb-3" id="lokasiPerusahaan" name="lokasiPerusahaan" placeholder="Masukkan Lokasi Perusahaan">

          <label for="websitePerusahaan" class="poppins-bold text-black mb-2">Website Perusahaan:</label>
          <input type="url" class="form-control mb-3" id="websitePerusahaan" name="websitePerusahaan" placeholder="Masukkan URL Website Perusahaan">

          <label for="industriPerusahaan" class="poppins-bold text-black mb-2">Industri:</label>
          <input type="text" class="form-control mb-3" id="industriPerusahaan" name="industriPerusahaan" placeholder="Masukkan URL Website Perusahaan">

          <label for="deskripsiPerusahaan" class="form-label poppins-bold text-black mb-2">Deskripsi Perusahaan:</label>
          <textarea class="form-control mb-3" id="deskripsiPerusahaan" name="deskripsiPerusahaan" placeholder="Masukkan Deskripsi Perusahaan"></textarea>  

          <label for="logo" class="form-label poppins-bold text-black mt-2">Tambahkan Gambar/Logo</label>
          <div class="button-wrap">
              <label class="buttonUploadFile" for="logo">
                  <i class='bx bx-upload me-1'></i> Choose a File
              </label>
              <input type="file" class="form-control d-none" id="logo" name="logo" multiple onchange="previewFiles()">
          </div>
          <div id="file-preview" class="mt-2"></div>
        </div>

        <label for="jenisLowongan" class="poppins-bold text-black mb-2">Departemen:</label>
        <input type="text" class="form-control mb-3" id="jenisLowongan" name="jenisLowongan" placeholder="Contoh: FullStack, Administrasi" required>

        <label for="tipeLowongan" class="poppins-bold text-black mb-2">Tipe Lowongan:</label>
        <select name="tipeLowongan" class="form-select" required>
              <option value="Full-time">Full-time</option>
              <option value="Part-time">Part-time</option>
              <option value="Magang">Magang</option>
              <option value="Kontrak">Kontrak</option>
        </select>

        <label for="deskripsiLowongan" class="form-label poppins-bold text-black mb-2">Deskripsi Lowongan:</label>
        <textarea class="form-control mb-3" id="deskripsiLowongan" name="deskripsiLowongan" placeholder="Masukkan Deskripsi Lowongan" required></textarea>

        <label for="kualifikasi" class="form-label poppins-bold text-black mb-2">Kualifikasi Lowongan:</label>
        <textarea class="form-control mb-3" id="kualifikasi" name="kualifikasi" placeholder="Masukkan Kualifikasi Lowongan" required></textarea>  

        <label for="benefit" class="form-label poppins-bold text-black mb-2">Benefit Lowongan:</label>
        <textarea class="form-control mb-3" id="benefit" name="benefit" placeholder="Masukkan Benefit Lowongan" required></textarea>  

        <label for="keahlian" class="poppins-bold text-black mb-2">Tambahkan Keahlian Lowongan:</label>
<div id="keahlianContainer">
@php
    $keahlian = old('keahlian', $lowongan->keahlian ?? '');
    $keahlianArray = is_string($keahlian) ? explode(',', $keahlian) : $keahlian;
    $keahlianArray = array_filter((array) $keahlianArray); // Pastikan selalu array
@endphp


<div id="keahlianContainer">
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
</div>


<div>
        <label for="batasMulai" class="poppins-bold text-black mb-2">Batas Mulai Lamaran:</label>
        <input type="date" class="form-control mb-3" id="batasMulai" name="batasMulai" required>

        <label for="batasAkhir" class="poppins-bold text-black mb-2">Batas Akhir Lamaran:</label>
        <input type="date" class="form-control mb-3" id="batasAkhir" name="batasAkhir" required>
       
        <div class="d-flex justify-content-end align-items-end gap-2">
        <a href="{{ route('admin.lowonganPekerjaan.lowonganPekerjaan') }}" class="btn btn-batal">Batal</a>
        <button type="submit" class="btn">Tambah</button>
      </div>

      </form>
  </div>
</div>

<script>
  function toggleOtherInput() {
    var selectBox = document.getElementById('namaPerusahaan');
    var otherInputContainer = document.getElementById('otherInputContainer');
    otherInputContainer.style.display = selectBox.value === 'Other' ? 'block' : 'none';
  }
</script>

<script>
  function addKeahlian() {
    let container = document.getElementById('keahlianContainer');
    let newInput = document.createElement('div');
    newInput.classList.add('keahlian-item', 'd-flex', 'mb-2');
    newInput.innerHTML = `
    <div class="d-flex flex-row align-items-center w-100">
        <input type="text" class="form-control me-2" name="keahlian[]" placeholder="Masukkan Keahlian" required>
        <button type="button" class="btn btn-danger btn-sm mt-0" onclick="removeKeahlian(this)">❌</button>
    </div>
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



<script>
  function previewFiles() {
      let input = document.getElementById('logo');
      let preview = document.getElementById('file-preview');
      preview.innerHTML = ''; // Reset tampilan sebelumnya

      if (input.files.length > 0) {
          for (let i = 0; i < input.files.length; i++) {
              let file = input.files[i];
              let fileReader = new FileReader();

              fileReader.onload = function (e) {
                  let fileType = file.type.split('/')[0]; // Cek tipe file
                  let fileDisplay = document.createElement('div');
                  fileDisplay.classList.add('mb-2');

                  if (fileType === 'image') {
                      fileDisplay.innerHTML = `<img src="${e.target.result}" alt="Preview" class="img-thumbnail" width="100">`;
                  } else {
                      fileDisplay.innerHTML = `<p>${file.name}</p>`;
                  }

                  preview.appendChild(fileDisplay);
              };

              fileReader.readAsDataURL(file);
          }
      }
  }
</script>
@endsection
