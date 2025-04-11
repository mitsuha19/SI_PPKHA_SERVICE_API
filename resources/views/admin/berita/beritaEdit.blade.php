@extends('layouts.appAdmin')

@section('content')
    @include('components.navbarAdmin')
    <div class="main-content">
        <h1 class="poppins-bold text-black mt-3" style="font-size: 22px">Edit Berita</h1>
        <div class="box-form">
            <form action="{{ route('berita.update', $berita->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="judul_berita">Judul Berita</label>
                    <input type="text" class="form-control" id="judul_berita" name="judul_berita"
                        value="{{ old('judul_berita', $berita->judul_berita) }}" required>
                </div>

                <div class="mb-3">
                    <label for="deskripsi_berita">Deskripsi Berita</label>
                    <textarea class="form-control" id="deskripsi_berita" name="deskripsi_berita" rows="10" required>{{ old('deskripsi_berita', $berita->deskripsi_berita) }}</textarea>
                </div>

                <!-- Upload gambar baru -->
                <div class="mb-3">
                    <label class="buttonUploadFile" for="upload">
                        <i class='bx bx-upload me-1'></i> Pilih Gambar Baru
                    </label>
                    <input type="file" class="form-control" id="upload" name="gambar[]" multiple accept="image/*"
                        onchange="previewFiles()">
                    <div id="preview-container" class="mt-2 d-flex flex-wrap gap-2"></div>
                </div>

                <!-- Menampilkan gambar lama -->
                <div class="mb-3">
                    <label>Gambar Lama:</label>
                    <div class="old-images d-flex flex-wrap gap-3">
                        @if (!empty($berita->gambar))
                            @php
                                $gambarArray = is_array($berita->gambar)
                                    ? $berita->gambar
                                    : json_decode($berita->gambar, true);
                            @endphp

                            @if (is_array($gambarArray))
                                @foreach ($gambarArray as $index => $file)
                                    <div id="lampiran-item-{{ $index }}"
                                        class="position-relative d-inline-block text-center" style="width: 120px;">
                                        <img src="{{ asset('storage/' . $file) }}" alt="Gambar Berita"
                                            class="rounded border w-100" style="height: 80px; object-fit: cover;">

                                        <!-- Tombol Remove (Di tengah bawah gambar) -->
                                        <button type="button" class="remove-lampiran position-absolute"
                                            data-file="{{ $file }}" data-target="lampiran-item-{{ $index }}"
                                            style="bottom: -10px; left: 50%; transform: translateX(-50%);
                                            background: white; color: red; font-size: 14px; cursor: pointer; 
                                            width: 24px; height: 24px; border-radius: 50%; 
                                            display: flex; align-items: center; justify-content: center; 
                                            box-shadow: 0px 0px 4px rgba(0,0,0,0.2); border: 1px solid red;">
                                            ❌
                                        </button>

                                        <input type="hidden" name="gambar_lama[]" value="{{ $file }}">
                                    </div>
                                @endforeach
                            @else
                                <p>Tidak ada gambar</p>
                            @endif
                        @endif
                    </div>
                </div>

                <div class="d-flex justify-content-end align-items-end gap-2 mt-3">
                    <a href="{{ route('admin.berita.berita') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Edit</button>
                </div>

            </form>
        </div>
    </div>

    <script>
        function previewFiles() {
            let input = document.getElementById('upload');
            let preview = document.getElementById('preview-container');
            preview.innerHTML = '';

            if (input.files.length > 0) {
                for (let i = 0; i < input.files.length; i++) {
                    let file = input.files[i];
                    let fileReader = new FileReader();

                    fileReader.onload = function(e) {
                        let imgWrapper = document.createElement('div');
                        imgWrapper.style.position = 'relative';
                        imgWrapper.style.width = '120px';
                        imgWrapper.classList.add('text-center');

                        let img = document.createElement('img');
                        img.src = e.target.result;
                        img.width = 100;
                        img.classList.add('rounded', 'border', 'w-100');

                        let removeButton = document.createElement('button');
                        removeButton.type = 'button';
                        removeButton.innerHTML = '❌';
                        removeButton.style.cssText = `
                            position: absolute; bottom: -10px; left: 50%; transform: translateX(-50%);
                            background: white; color: red; font-size: 14px; cursor: pointer;
                            width: 24px; height: 24px; border-radius: 50%;
                            display: flex; align-items: center; justify-content: center;
                            box-shadow: 0px 0px 4px rgba(0,0,0,0.2); border: 1px solid red;
                        `;

                        removeButton.onclick = function() {
                            imgWrapper.remove();
                        };

                        imgWrapper.appendChild(img);
                        imgWrapper.appendChild(removeButton);
                        preview.appendChild(imgWrapper);
                    };

                    fileReader.readAsDataURL(file);
                }
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".remove-lampiran").forEach(button => {
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
    </script>
@endsection
