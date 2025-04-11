@extends('layouts.appAdmin')

@section('content')
@include('components.navbarAdmin')
<div class="main-content">
  <h1 class="poppins-bold text-black mt-3" style="font-size: 22px">Edit Artikel</h1>
  <div class="box-form">
      <form action="{{ route('admin.artikel.update', $artikel->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        @method('PUT')
        <label for="judul" class="poppins-bold text-black mb-2">Judul Artikel:</label>
        <input type="text" class="form-control mb-3" id="judul" name="judul_artikel"
        value="{{ $artikel->judul_artikel }}" placeholder="Masukkan judul Artikel" required>

        <label for="deskripsi" class="form-label poppins-bold text-black mb-2">Deskripsi Artikel:</label>
        <textarea class="form-control mb-3" id="deskripsi" name="deskripsi_artikel"
        placeholder="Masukkan deskripsi Artikel" required>
          {{ $artikel->deskripsi_artikel }}
        </textarea>  

        <label for="sumber" class="form-label poppins-bold text-black mb-2">Sumber Artikel:</label>
        <input class="form-control mb-3" id="sumber" name="sumber_artikel" value="{{ $artikel->sumber_artikel }}"></input>

        <label for="myFile" class="form-label poppins-bold text-black mt-2">Tambahkan Gambar:</label>
        <div class="button-wrap">
          <label class="buttonUploadFile" for="upload">
            <i class='bx bx-upload me-1'></i>
            Choose a File
          </label>
          <input id="upload" type="file" name="gambar[]" multiple  onchange="previewFiles()">
        </div>

        <div id="preview-container" class="mt-3 d-flex flex-wrap gap-2"></div>

        {{-- Menampilkan Gambar yang Sudah Ada --}}
        @if (!empty($artikel->gambar))
        <div class="mt-3">
            <p class="poppins-bold text-black" style="font-size: 14px;">Lampiran Saat Ini:</p>
            <ul id="gambar-list" style="list-style: none; padding: 0;">
              @if (!empty($artikel->gambar))
              @php
                  $gambarArray = is_array($artikel->gambar) ? $artikel->gambar : json_decode($artikel->gambar, true);
              @endphp
          
              @if(is_array($gambarArray))
                  @foreach ($gambarArray as $index => $file)
                      <div id="lampiran-item-{{ $index }}" class="d-flex align-items-center mb-2">
                          <img src="{{ asset('storage/' . $file) }}" alt="Gambar Artikel" width="100" class="me-2">
                          <label class="ms-2">
                              <input type="checkbox" name="hapus_gambar[]" value="{{ $file }}"> Hapus
                          </label>
                      </div>
                  @endforeach
              @else
                  <p>Tidak ada gambar</p>
              @endif
          @else
              <p>Tidak ada gambar</p>
          @endif
            </ul>
        </div>
        @endif


        <div class="d-flex justify-content-end align-items-end gap-2">
          <a href="{{ route('admin.artikel.artikel') }}" class="btn btn-batal">Batal</a>
          <button type="submit" class="btn">Edit</button>
        </div>
      </form>
  </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function() {
      // Auto scroll ke bawah saat halaman dimuat
      window.scrollTo(0, document.body.scrollHeight);

      // Fungsi untuk menghapus lampiran
      document.querySelectorAll(".remove-gambar").forEach(button => {
          button.addEventListener("click", function() {
              let file = this.getAttribute("data-file");
              let targetId = this.getAttribute("data-target");
              document.getElementById(targetId).remove();

              let hiddenInput = document.createElement("input");
              hiddenInput.type = "hidden";
              hiddenInput.name = "hapus_gambar[]";
              hiddenInput.value = file;
              document.querySelector("form").appendChild(hiddenInput);
          });
      });
  });

  function previewFiles() {
      const input = document.getElementById('upload');
      const previewContainer = document.getElementById('preview-container');
      previewContainer.innerHTML = '';

      if (input.files.length > 0) {
          Array.from(input.files).forEach((file, index) => {
              const reader = new FileReader();

              reader.onload = function (e) {
                  let imgWrapper = document.createElement('div');
                  imgWrapper.style.position = 'relative';
                  imgWrapper.style.width = '120px';
                  imgWrapper.classList.add('text-center');

                  let img = document.createElement('img');
                  img.src = e.target.result;
                  img.width = 100;
                  img.classList.add('rounded', 'border', 'w-100');

                  // Membuat tombol hapus
                  let removeButton = document.createElement('button');
                  removeButton.type = 'button';
                  removeButton.innerHTML = '❌';
                  removeButton.style.cssText = `
                      position: absolute; bottom: 5px; left: 50%; transform: translateX(-50%);
                      background: white; color: red; font-size: 14px; cursor: pointer;
                      width: 24px; height: 24px; border-radius: 50%;
                      display: flex; align-items: center; justify-content: center;
                      box-shadow: 0px 0px 4px rgba(0,0,0,0.2); border: 1px solid red;
                      z-index: 10;
                  `;

                  // Event untuk menghapus gambar
                  removeButton.onclick = function () {
                      imgWrapper.remove();
                  };

                  imgWrapper.appendChild(img);
                  imgWrapper.appendChild(removeButton);
                  previewContainer.appendChild(imgWrapper);
              };

              reader.readAsDataURL(file);
          });
      }
  }
</script>
@endsection
