@extends('layouts.appAdmin')

@section('content')
    @include('components.navbarAdmin')

    <div class="main-content">
        <h1 class="poppins-bold text-black mt-3" style="font-size: 22px">Tambah Berita</h1>
        <div class="box-form">
            <form action="{{ route('admin.berita.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="judul_berita" class="poppins-bold text-black mb-2">Judul Berita:</label>
                <input type="text" class="form-control mb-3" id="judul_berita" name="judul_berita"
                    placeholder="Masukkan judul berita" required>

                <label for="deskripsi_berita" class="form-label poppins-bold text-black mb-2">Deskripsi Berita:</label>
                <textarea class="form-control mb-3" id="deskripsi_berita" name="deskripsi_berita"
                    placeholder="Masukkan deskripsi berita" required></textarea>

                <label for="gambar" class="form-label poppins-bold text-black mt-2">Tambahkan Gambar:</label>
                <div class="button-wrap">
                    <label class="buttonUploadFile" for="gambar">
                        <i class='bx bx-upload me-1'></i> Pilih File
                    </label>
                    <input type="file" class="form-control d-none" id="gambar" name="gambar[]" multiple
                        accept="image/*" onchange="previewFiles()">
                </div>

                <div id="preview-container" class="mt-3" style="max-height: 200px; overflow-y: auto;"></div>

                <div class="d-flex justify-content-end align-items-end gap-2 mt-3">
                    <a href="{{ route('admin.berita.berita') }}" class="btn btn-batal">Batal</a>
                    <button type="submit" class="btn">Tambah</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let selectedFiles = [];

        function previewFiles() {
            const previewContainer = document.getElementById("preview-container");
            const inputFiles = document.getElementById("gambar").files;

            for (let i = 0; i < inputFiles.length; i++) {
                const exists = selectedFiles.some(file => file.name === inputFiles[i].name && file.size === inputFiles[i]
                    .size);
                if (!exists) {
                    selectedFiles.push(inputFiles[i]);
                }
            }

            renderPreview();

            setTimeout(() => {
                previewContainer.scrollTop = previewContainer.scrollHeight;
                window.scrollTo({
                    top: document.body.scrollHeight,
                    behavior: "smooth"
                });
            }, 100);
        }

        function renderPreview() {
            const previewContainer = document.getElementById("preview-container");
            previewContainer.innerHTML = "";

            selectedFiles.forEach((file, index) => {
                const fileWrapper = document.createElement("div");
                fileWrapper.style.display = "flex";
                fileWrapper.style.alignItems = "center";
                fileWrapper.style.marginBottom = "5px";
                fileWrapper.style.padding = "5px";
                fileWrapper.style.border = "1px solid #ddd";
                fileWrapper.style.borderRadius = "5px";
                fileWrapper.style.justifyContent = "space-between";

                const fileElement = document.createElement("img");
                fileElement.src = URL.createObjectURL(file);
                fileElement.style.width = "100px";
                fileElement.style.height = "100px";
                fileElement.style.objectFit = "cover";
                fileElement.style.marginRight = "10px";
                fileWrapper.appendChild(fileElement);

                const deleteButton = document.createElement("button");
                deleteButton.innerHTML = "❌";
                deleteButton.setAttribute("type", "button");
                deleteButton.classList.add("remove-lampiran");
                deleteButton.style.background = "none";
                deleteButton.style.border = "none";
                deleteButton.style.color = "red";
                deleteButton.style.fontSize = "10px";
                deleteButton.style.padding = "2px";
                deleteButton.style.margin = "0";
                deleteButton.style.width = "15px";
                deleteButton.style.height = "15px";
                deleteButton.style.lineHeight = "1";
                deleteButton.style.display = "flex";
                deleteButton.style.alignItems = "center";
                deleteButton.style.justifyContent = "center";
                deleteButton.style.cursor = "pointer";

                deleteButton.onclick = () => removeFile(index);
                fileWrapper.appendChild(deleteButton);

                previewContainer.appendChild(fileWrapper);
            });

            updateFileInput();
        }

        function removeFile(index) {
            selectedFiles.splice(index, 1);
            renderPreview();

            setTimeout(() => {
                const previewContainer = document.getElementById("preview-container");
                previewContainer.scrollTop = previewContainer.scrollHeight;
                window.scrollTo({
                    top: document.body.scrollHeight,
                    behavior: "smooth"
                });
            }, 100);
        }

        function updateFileInput() {
            const dataTransfer = new DataTransfer();
            selectedFiles.forEach(file => dataTransfer.items.add(file));
            document.getElementById("gambar").files = dataTransfer.files;
        }
    </script>
@endsection
